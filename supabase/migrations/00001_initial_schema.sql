-- ユーザープロファイル
create table profiles (
  id uuid references auth.users on delete cascade primary key,
  company_name text,
  created_at timestamptz default now()
);

-- チャットボット設定
create table chatbots (
  id uuid primary key default gen_random_uuid(),
  user_id uuid references profiles(id) on delete cascade not null,
  name text not null,
  token uuid default gen_random_uuid() unique not null,
  greeting text default 'こんにちは！なんでもお聞きください！',
  created_at timestamptz default now()
);

-- FAQ
create table faqs (
  id uuid primary key default gen_random_uuid(),
  chatbot_id uuid references chatbots(id) on delete cascade not null,
  question text not null,
  answer text not null,
  sort_order int default 0,
  created_at timestamptz default now(),
  updated_at timestamptz default now()
);

-- チャット会話
create table conversations (
  id uuid primary key default gen_random_uuid(),
  chatbot_id uuid references chatbots(id) on delete cascade not null,
  created_at timestamptz default now()
);

-- メッセージ
create table messages (
  id uuid primary key default gen_random_uuid(),
  conversation_id uuid references conversations(id) on delete cascade not null,
  role text check (role in ('user', 'assistant')) not null,
  content text not null,
  created_at timestamptz default now()
);

-- お問い合わせ
create table contacts (
  id uuid primary key default gen_random_uuid(),
  name text not null,
  email text not null,
  message text not null,
  created_at timestamptz default now()
);

-- RLS有効化
alter table profiles enable row level security;
alter table chatbots enable row level security;
alter table faqs enable row level security;
alter table conversations enable row level security;
alter table messages enable row level security;
alter table contacts enable row level security;

-- profiles ポリシー
create policy "Users can view own profile" on profiles
  for select using (auth.uid() = id);
create policy "Users can update own profile" on profiles
  for update using (auth.uid() = id);
create policy "Users can insert own profile" on profiles
  for insert with check (auth.uid() = id);

-- chatbots ポリシー
create policy "Users can view own chatbots" on chatbots
  for select using (auth.uid() = user_id);
create policy "Users can create chatbots" on chatbots
  for insert with check (auth.uid() = user_id);
create policy "Users can update own chatbots" on chatbots
  for update using (auth.uid() = user_id);
create policy "Users can delete own chatbots" on chatbots
  for delete using (auth.uid() = user_id);

-- faqs ポリシー
create policy "Users can view own faqs" on faqs
  for select using (
    chatbot_id in (select id from chatbots where user_id = auth.uid())
  );
create policy "Users can create faqs" on faqs
  for insert with check (
    chatbot_id in (select id from chatbots where user_id = auth.uid())
  );
create policy "Users can update own faqs" on faqs
  for update using (
    chatbot_id in (select id from chatbots where user_id = auth.uid())
  );
create policy "Users can delete own faqs" on faqs
  for delete using (
    chatbot_id in (select id from chatbots where user_id = auth.uid())
  );

-- faqs: チャットAPIからの匿名読み取り（tokenベース）
create policy "Public can read faqs by chatbot token" on faqs
  for select using (true);

-- conversations ポリシー
create policy "Users can view own conversations" on conversations
  for select using (
    chatbot_id in (select id from chatbots where user_id = auth.uid())
  );
create policy "Anyone can create conversations" on conversations
  for insert with check (true);

-- messages ポリシー
create policy "Users can view own messages" on messages
  for select using (
    conversation_id in (
      select c.id from conversations c
      join chatbots cb on c.chatbot_id = cb.id
      where cb.user_id = auth.uid()
    )
  );
create policy "Anyone can create messages" on messages
  for insert with check (true);
create policy "Anyone can read messages by conversation" on messages
  for select using (true);

-- contacts ポリシー
create policy "Anyone can create contacts" on contacts
  for insert with check (true);

-- chatbots: 公開トークンでの読み取り
create policy "Public can read chatbot by token" on chatbots
  for select using (true);

-- conversations: 公開読み取り
create policy "Public can read conversations" on conversations
  for select using (true);

-- 新規ユーザー登録時に自動でプロファイルとデフォルトチャットボットを作成する関数
create or replace function handle_new_user()
returns trigger as $$
begin
  insert into profiles (id) values (new.id);
  insert into chatbots (user_id, name) values (new.id, 'マイチャットボット');
  return new;
end;
$$ language plpgsql security definer;

-- トリガー
create trigger on_auth_user_created
  after insert on auth.users
  for each row execute procedure handle_new_user();
