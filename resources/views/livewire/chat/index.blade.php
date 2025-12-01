<?php

use Livewire\Volt\Component;
use Illuminate\Support\Facades\Http;
use App\Services\Fortune\TarotService;

new class extends Component {
    public array $messages = [];
    public array $categories = [];
    public ?string $selectedCategoryId = null;
    public ?string $selectedQuestionId = null;
    public bool $showCardSelection = false;
    public ?array $tarotResult = null;
    public bool $isLoading = false;
    public bool $showUpgrade = false;

    public function mount()
    {
        // APIからカテゴリー情報を取得する代わりに、直接定義（またはサービスクラス経由）
        // ここでは簡易的にコントローラーと同じ定義を持つか、APIを叩くか。
        // APIは内部呼び出しよりサービスクラス呼び出しの方が効率的ですが、今回はモックとして定義します。
        $this->categories = [
            [
                'id' => 'love',
                'label' => '結婚・恋愛',
                'icon' => '💕',
                'questions' => [
                    ['id' => 'feelings', 'label' => 'あの人の気持ち'],
                    ['id' => 'encounter', 'label' => '出会いの時期'],
                    ['id' => 'reunion', 'label' => '復縁の可能性'],
                    ['id' => 'future', 'label' => 'パートナーとの今後'],
                ]
            ],
            [
                'id' => 'work',
                'label' => '仕事',
                'icon' => '💼',
                'questions' => [
                    ['id' => 'career_change', 'label' => '転職を考えている'],
                    ['id' => 'relationship', 'label' => '職場の人間関係'],
                    ['id' => 'evaluation', 'label' => '今の評価への不満'],
                    ['id' => 'talent', 'label' => '自分の才能・適職'],
                ]
            ],
            [
                'id' => 'human_relations',
                'label' => '人間関係',
                'icon' => '🤝',
                'questions' => [
                    ['id' => 'family', 'label' => '家族・親族'],
                    ['id' => 'friend', 'label' => '友人・知人'],
                    ['id' => 'colleague', 'label' => '苦手な上司・同僚'],
                    ['id' => 'general', 'label' => '全体的な対人運'],
                ]
            ],
            [
                'id' => 'self_understanding',
                'label' => '自己理解',
                'icon' => '🧘',
                'questions' => [
                    ['id' => 'strength', 'label' => '自分の強みがわからない'],
                    ['id' => 'passion', 'label' => 'やりたいことが見つからない'],
                    ['id' => 'anxiety', 'label' => 'なんとなく将来が不安'],
                    ['id' => 'fortune_flow', 'label' => '運気の流れを知りたい'],
                ]
            ],
        ];
    }

    public function selectCategory(string $categoryId)
    {
        $this->selectedCategoryId = $categoryId;
        $category = collect($this->categories)->firstWhere('id', $categoryId);
        
        $this->messages[] = [
            'type' => 'user',
            'content' => $category['label'] . 'について相談したいです。',
        ];

        // 占い師の挨拶
        $this->isLoading = true;
        
        // 少し遅延させて占い師の反応を表示
        $this->dispatch('scroll-to-bottom');
        
        // ここで次のステップ（質問選択）へ進む処理を遅延実行させるイメージですが
        // Livewireでは即時実行して、フロントで遅延表示などの演出を加えるのが一般的
        
        $greeting = match($categoryId) {
            'love' => '恋の悩みですね。あなたの心が少しでも軽くなるよう、カードに聞いてみましょう。',
            'work' => 'お仕事のことですね。あなたが輝ける場所やタイミングを、一緒に探っていきましょう。',
            'human_relations' => '人間関係は難しいですよね。絡まった糸を解くヒントが、きっと見つかりますよ。',
            'self_understanding' => 'ご自身と向き合うのですね。素晴らしい一歩です。あなたの魂の声を聞いてみましょう。',
            default => 'ご相談ですね。詳しく教えていただけますか？',
        };

        $this->messages[] = [
            'type' => 'bot',
            'content' => $greeting,
        ];
    }

    public function selectQuestion(string $questionId)
    {
        $this->selectedQuestionId = $questionId;
        $category = collect($this->categories)->firstWhere('id', $this->selectedCategoryId);
        $question = collect($category['questions'])->firstWhere('id', $questionId);

        $this->messages[] = [
            'type' => 'user',
            'content' => $question['label'],
        ];

        $this->messages[] = [
            'type' => 'bot',
            'content' => '承知しました。では、心を落ち着けて、カードを1枚選んでください。',
        ];

        $this->showCardSelection = true;
        $this->dispatch('scroll-to-bottom');
    }

    public function drawCard()
    {
        $this->showCardSelection = false;
        $this->isLoading = true;

        // タロットサービスを利用
        $tarotService = app(TarotService::class);
        $this->tarotResult = $tarotService->drawOne();

        // APIコントローラーのロジックを模倣（実際にはここでBedrock等を呼ぶ）
        // モックメッセージ生成
        $cardName = $this->tarotResult['card_name'];
        $position = $this->tarotResult['position'];
        
        // カテゴリごとのメッセージ生成
        $message = match($this->selectedCategoryId) {
            'love' => "「{$cardName}」の{$position}が出ましたね。恋愛についてのご相談、心が揺れ動いている様子が伝わってきます。このカードは...",
            'work' => "お仕事について、「{$cardName}」の{$position}が示されました。キャリアの岐路において重要な意味を持つカードです...",
            'human_relations' => "人間関係のお悩みですね。「{$cardName}」の{$position}は、周囲との調和や距離感について示唆を与えてくれています...",
            'self_understanding' => "ご自身と向き合う良い機会です。「{$cardName}」の{$position}が、あなたの内なる声や本来の強みを映し出しています...",
            default => "鑑定結果をお伝えします。「{$cardName}」の{$position}が出ています。",
        };

        // アドバイス生成
        $advice = match($this->selectedCategoryId) {
            'love' => ['title' => '自分をいたわる時間を作ろう', 'body' => 'まずは温かいお茶を飲んでリラックスを。焦りは禁物です。'],
            'work' => ['title' => '優先順位の整理を', 'body' => 'タスクを書き出し、本当に重要なものに集中してみましょう。'],
            'human_relations' => ['title' => '距離感を再確認', 'body' => '無理に合わせすぎず、自分のペースを大切にしてください。'],
            'self_understanding' => ['title' => '感情のジャーナリング', 'body' => '今の気持ちを紙に書き出すことで、思考がクリアになります。'],
            default => ['title' => '深呼吸を一つ', 'body' => 'まずは一息ついて、落ち着いて状況を見渡しましょう。'],
        ];

        $this->messages[] = [
            'type' => 'bot',
            'content' => $message,
            'is_result' => true,
            'card' => $this->tarotResult,
            'advice' => $advice,
        ];

        $this->isLoading = false;
        $this->showUpgrade = true;
        $this->dispatch('scroll-to-bottom');
    }
}; ?>

<div class="flex flex-col h-full max-w-2xl mx-auto bg-white/90 dark:bg-[#2A2E47]/90 backdrop-blur-sm rounded-xl shadow-lg border border-[#F8A38A]/30 overflow-hidden" style="min-height: 600px;">
    <!-- Header -->
    <div class="bg-[#FFFDF9] dark:bg-[#2A2E47] p-4 border-b border-[#F8A38A]/20 flex items-center gap-3">
        <div class="w-10 h-10 rounded-full bg-gradient-to-br from-[#F8A38A] to-[#E985A6] flex items-center justify-center text-white text-xl">
            🔮
        </div>
        <div>
            <h3 class="font-bold text-[#2A2E47] dark:text-[#FFFDF9]">Fortune Chat</h3>
            <p class="text-xs text-[#2A2E47]/60 dark:text-[#FFFDF9]/60">あなたの悩みに寄り添います</p>
        </div>
    </div>

    <!-- Chat Area -->
    <div class="flex-1 overflow-y-auto p-4 space-y-4" id="chat-messages">
        <!-- Initial Bot Message -->
        <div class="flex gap-3 items-start">
            <div class="w-8 h-8 rounded-full bg-gradient-to-br from-[#F8A38A] to-[#E985A6] flex items-center justify-center text-white text-sm flex-shrink-0">
                🔮
            </div>
            <div class="bg-[#FFFDF9] dark:bg-[#2A2E47] border border-[#F8A38A]/20 rounded-2xl rounded-tl-none p-4 shadow-sm max-w-[85%]">
                <p class="text-sm text-[#2A2E47] dark:text-[#FFFDF9] leading-relaxed">
                    こんにちは。今日はどのようなことでお悩みですか？<br>
                    以下からテーマを選んでくださいね。
                </p>
            </div>
        </div>

        <!-- Category Selection -->
        @if(!$selectedCategoryId)
            <div class="grid grid-cols-2 gap-3 ml-11">
                @foreach($categories as $category)
                    <button 
                        wire:click="selectCategory('{{ $category['id'] }}')"
                        class="flex flex-col items-center justify-center p-4 bg-white dark:bg-[#2A2E47] border border-[#F8A38A]/30 rounded-xl hover:bg-[#F8A38A]/10 transition-colors shadow-sm"
                    >
                        <span class="text-2xl mb-2">{{ $category['icon'] }}</span>
                        <span class="text-sm font-bold text-[#2A2E47] dark:text-[#FFFDF9]">{{ $category['label'] }}</span>
                    </button>
                @endforeach
            </div>
        @endif

        <!-- Messages Loop -->
        @foreach($messages as $msg)
            <div class="flex gap-3 items-start {{ $msg['type'] === 'user' ? 'flex-row-reverse' : '' }}">
                <div class="w-8 h-8 rounded-full {{ $msg['type'] === 'user' ? 'bg-gray-200 dark:bg-gray-700' : 'bg-gradient-to-br from-[#F8A38A] to-[#E985A6]' }} flex items-center justify-center text-white text-sm flex-shrink-0">
                    {{ $msg['type'] === 'user' ? '👤' : '🔮' }}
                </div>
                <div class="{{ $msg['type'] === 'user' ? 'bg-[#F8A38A]/10 dark:bg-[#F8A38A]/20' : 'bg-[#FFFDF9] dark:bg-[#2A2E47] border border-[#F8A38A]/20' }} rounded-2xl {{ $msg['type'] === 'user' ? 'rounded-tr-none' : 'rounded-tl-none' }} p-4 shadow-sm max-w-[85%]">
                    <p class="text-sm text-[#2A2E47] dark:text-[#FFFDF9] leading-relaxed whitespace-pre-wrap">{{ $msg['content'] }}</p>
                    
                    @if(isset($msg['is_result']) && $msg['is_result'])
                        <!-- Tarot Result Card -->
                        <div class="mt-4 bg-white dark:bg-[#1a1d2d] rounded-xl p-4 border border-[#F8A38A]/20">
                            <div class="flex gap-4 mb-4">
                                <div class="w-20 h-32 bg-gray-100 rounded flex items-center justify-center flex-shrink-0">
                                    @if($msg['card']['card_image'])
                                        <img src="{{ $msg['card']['card_image'] }}" class="w-full h-full object-contain {{ $msg['card']['position'] === '逆位置' ? 'rotate-180' : '' }}" alt="Tarot Card">
                                    @else
                                        🃏
                                    @endif
                                </div>
                                <div>
                                    <p class="font-bold text-[#F8A38A]">{{ $msg['card']['card_name'] }}</p>
                                    <p class="text-xs text-gray-500">{{ $msg['card']['position'] }}</p>
                                    <p class="text-xs mt-2 text-[#2A2E47]/80 dark:text-[#FFFDF9]/80">{{ \Illuminate\Support\Str::limit($msg['card']['message'], 100) }}</p>
                                </div>
                            </div>
                            
                            <!-- Advice Section -->
                            @if(isset($msg['advice']))
                                <div class="bg-[#F8A38A]/10 rounded-lg p-3">
                                    <p class="text-xs font-bold text-[#F8A38A] mb-1">🔮 アドバイス: {{ $msg['advice']['title'] }}</p>
                                    <p class="text-xs text-[#2A2E47]/80 dark:text-[#FFFDF9]/80">{{ $msg['advice']['body'] }}</p>
                                </div>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        @endforeach

        <!-- Question Selection -->
        @if($selectedCategoryId && !$selectedQuestionId)
            @php
                $currentCategory = collect($categories)->firstWhere('id', $selectedCategoryId);
            @endphp
            <div class="ml-11 space-y-2">
                @foreach($currentCategory['questions'] as $question)
                    <button 
                        wire:click="selectQuestion('{{ $question['id'] }}')"
                        class="w-full text-left p-3 bg-white dark:bg-[#2A2E47] border border-[#F8A38A]/30 rounded-lg hover:bg-[#F8A38A]/10 transition-colors text-sm text-[#2A2E47] dark:text-[#FFFDF9]"
                    >
                        {{ $question['label'] }}
                    </button>
                @endforeach
            </div>
        @endif

        <!-- Card Selection Animation -->
        @if($showCardSelection)
            <div class="ml-11 py-4">
                <p class="text-xs text-center text-gray-500 mb-4">カードを1枚タップしてください</p>
                <div class="flex justify-center gap-4">
                    @for($i = 0; $i < 3; $i++)
                        <button 
                            wire:click="drawCard"
                            class="w-20 h-32 bg-gradient-to-br from-[#2A2E47] to-[#1a1d2d] rounded-lg border-2 border-[#F8A38A]/50 shadow-md hover:-translate-y-2 transition-transform duration-300 flex items-center justify-center"
                        >
                            <span class="text-2xl opacity-50">✨</span>
                        </button>
                    @endfor
                </div>
            </div>
        @endif

        <!-- Loading Indicator -->
        @if($isLoading)
            <div class="flex gap-3 items-start">
                <div class="w-8 h-8 rounded-full bg-gradient-to-br from-[#F8A38A] to-[#E985A6] flex items-center justify-center text-white text-sm flex-shrink-0 animate-pulse">
                    🔮
                </div>
                <div class="bg-[#FFFDF9] dark:bg-[#2A2E47] border border-[#F8A38A]/20 rounded-2xl rounded-tl-none p-4 shadow-sm">
                    <div class="flex gap-1">
                        <div class="w-2 h-2 bg-[#F8A38A] rounded-full animate-bounce" style="animation-delay: 0s"></div>
                        <div class="w-2 h-2 bg-[#F8A38A] rounded-full animate-bounce" style="animation-delay: 0.2s"></div>
                        <div class="w-2 h-2 bg-[#F8A38A] rounded-full animate-bounce" style="animation-delay: 0.4s"></div>
                    </div>
                </div>
            </div>
        @endif

    </div>
    
    @if($showUpgrade)
        <!-- Locked Content Cards -->
        <div class="p-4 space-y-4 bg-[#FFFDF9] dark:bg-[#2A2E47] border-t border-[#F8A38A]/20">
            <div class="text-center mb-2">
                <p class="text-sm font-bold text-[#2A2E47] dark:text-[#FFFDF9]">🔒 深掘りレポート（無料プレビュー）</p>
            </div>
            
            <div class="grid grid-cols-2 gap-3 overflow-x-auto pb-2">
                <!-- 1. 今すぐできる開運行動 -->
                <div class="min-w-[140px] p-3 rounded-lg bg-gradient-to-br from-[#F8A38A]/10 to-[#E985A6]/10 border border-[#F8A38A]/20 relative group cursor-pointer hover:shadow-md transition-shadow">
                    <div class="absolute top-2 right-2 text-gray-400">🔒</div>
                    <div class="text-2xl mb-2">✨</div>
                    <h4 class="text-xs font-bold text-[#2A2E47] dark:text-[#FFFDF9] mb-1">今すぐできる開運アクション</h4>
                    <p class="text-[10px] text-gray-500">7日以内に訪れるチャンスを掴むために</p>
                </div>

                <!-- 2. NG行動 -->
                <div class="min-w-[140px] p-3 rounded-lg bg-gradient-to-br from-gray-100 to-gray-200 dark:from-gray-800 dark:to-gray-700 border border-gray-200 dark:border-gray-600 relative group cursor-pointer hover:shadow-md transition-shadow">
                    <div class="absolute top-2 right-2 text-gray-400">🔒</div>
                    <div class="text-2xl mb-2">⚠️</div>
                    <h4 class="text-xs font-bold text-[#2A2E47] dark:text-[#FFFDF9] mb-1">ドツボにハマるNG行動</h4>
                    <p class="text-[10px] text-gray-500">無意識にやってしまう「運気を下げる」癖</p>
                </div>

                <!-- 3. ラッキーアイテム -->
                <div class="min-w-[140px] p-3 rounded-lg bg-gradient-to-br from-green-50 to-green-100 dark:from-green-900/20 dark:to-green-800/20 border border-green-200 dark:border-green-800 relative group cursor-pointer hover:shadow-md transition-shadow">
                    <div class="absolute top-2 right-2 text-gray-400">🔒</div>
                    <div class="text-2xl mb-2">🔮</div>
                    <h4 class="text-xs font-bold text-[#2A2E47] dark:text-[#FFFDF9] mb-1">五行で補うラッキーアイテム</h4>
                    <p class="text-[10px] text-gray-500">あなたに不足しているエネルギーを補う色と素材</p>
                </div>

                <!-- 4. 思考の癖 -->
                <div class="min-w-[140px] p-3 rounded-lg bg-gradient-to-br from-blue-50 to-blue-100 dark:from-blue-900/20 dark:to-blue-800/20 border border-blue-200 dark:border-blue-800 relative group cursor-pointer hover:shadow-md transition-shadow">
                    <div class="absolute top-2 right-2 text-gray-400">🔒</div>
                    <div class="text-2xl mb-2">🧠</div>
                    <h4 class="text-xs font-bold text-[#2A2E47] dark:text-[#FFFDF9] mb-1">思考の癖と「心のブロック」</h4>
                    <p class="text-[10px] text-gray-500">なぜか同じパターンで失敗してしまう理由</p>
                </div>

                <!-- 5. 紫微斗数 -->
                <div class="min-w-[140px] p-3 rounded-lg bg-gradient-to-br from-purple-50 to-purple-100 dark:from-purple-900/20 dark:to-purple-800/20 border border-purple-200 dark:border-purple-800 relative group cursor-pointer hover:shadow-md transition-shadow">
                    <div class="absolute top-2 right-2 text-gray-400">🔒</div>
                    <div class="text-2xl mb-2">🌟</div>
                    <h4 class="text-xs font-bold text-[#2A2E47] dark:text-[#FFFDF9] mb-1">紫微斗数・精密性格診断</h4>
                    <p class="text-[10px] text-gray-500">「占いの帝王」が暴く、あなたの隠れた才能</p>
                </div>

                <!-- 6. バイオリズム -->
                <div class="min-w-[140px] p-3 rounded-lg bg-gradient-to-br from-orange-50 to-orange-100 dark:from-orange-900/20 dark:to-orange-800/20 border border-orange-200 dark:border-orange-800 relative group cursor-pointer hover:shadow-md transition-shadow">
                    <div class="absolute top-2 right-2 text-gray-400">🔒</div>
                    <div class="text-2xl mb-2">📈</div>
                    <h4 class="text-xs font-bold text-[#2A2E47] dark:text-[#FFFDF9] mb-1">運気のバイオリズム予報</h4>
                    <p class="text-[10px] text-gray-500">好調期・低迷期を知り、攻めと守りの時期を見極める</p>
                </div>
            </div>

            <a href="{{ route('profile.edit') }}" class="block w-full py-3 bg-gradient-to-r from-[#F8A38A] to-[#E985A6] text-white text-center font-bold rounded-full shadow-lg hover:opacity-90 transition-opacity">
                続きは980円で詳しく見る
            </a>
            <div class="mt-4 bg-white/50 dark:bg-[#2A2E47]/50 rounded-lg p-4 border border-[#F8A38A]/10">
                <h4 class="text-xs font-bold text-center text-[#2A2E47]/70 dark:text-[#FFFDF9]/70 mb-3">無料プランと有料プランの比較</h4>
                <div class="flex justify-between text-xs mb-2 pb-2 border-b border-[#F8A38A]/10">
                    <span class="font-medium">機能</span>
                    <span class="text-gray-500">無料</span>
                    <span class="text-[#F8A38A] font-bold">プレミアム</span>
                </div>
                <div class="space-y-2 text-[11px]">
                    <div class="flex justify-between items-center">
                        <span>今日の運勢（簡易）</span>
                        <span>⚪︎</span>
                        <span class="text-[#F8A38A]">◎ 詳細</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span>My命式（基本）</span>
                        <span>⚪︎</span>
                        <span class="text-[#F8A38A]">◎ 全項目</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span>タロット占い</span>
                        <span>1枚引き</span>
                        <span class="text-[#F8A38A]">スプレッド展開</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span>悩み相談チャット</span>
                        <span>お試し</span>
                        <span class="text-[#F8A38A]">無制限</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span>深掘りレポート</span>
                        <span>×</span>
                        <span class="text-[#F8A38A]">閲覧可</span>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <script>
        document.addEventListener('livewire:initialized', () => {
            Livewire.on('scroll-to-bottom', () => {
                setTimeout(() => {
                    const chatContainer = document.getElementById('chat-messages');
                    chatContainer.scrollTop = chatContainer.scrollHeight;
                }, 100);
            });
        });
    </script>
</div>
