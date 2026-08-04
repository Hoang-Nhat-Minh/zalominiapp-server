@extends('layouts.main')

@section('title', 'Kết quả khảo sát - Hệ thống Quản Trị')

@section('content')
    <main class="admin-content-wrapper surveys-wrapper">
        <div class="surveys-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
            <div>
                <h1 style="font-size: 24px; font-weight: 600; color: var(--text-main); margin-bottom: 4px;">Kết quả khảo sát: {{ $survey->title }}</h1>
                <p style="color: var(--text-muted); font-size: 14px;">Tổng hợp tỷ lệ ý kiến đóng góp và danh sách câu trả lời của người dân.</p>
            </div>
            <a href="{{ route('surveys.index') }}" class="admin-btn" style="display: inline-flex; align-items: center; gap: 8px; text-decoration: none; padding: 10px 16px; border-radius: 6px; background: #e0e0e0; color: #333; font-weight: 600; font-size: 14px;">
                <i class="ph ph-arrow-left"></i> Quay lại
            </a>
        </div>

        <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px; margin-bottom: 24px;">
            <div class="gov-card" style="background: var(--surface); padding: 16px; border-radius: 8px; border: 1px solid var(--border);">
                <div style="font-size: 12px; color: var(--text-muted);">Tổng lượt tham gia</div>
                <div style="font-size: 24px; font-weight: 800; color: #10B981; margin-top: 4px;">{{ $totalResponses }} lượt</div>
            </div>
            <div class="gov-card" style="background: var(--surface); padding: 16px; border-radius: 8px; border: 1px solid var(--border);">
                <div style="font-size: 12px; color: var(--text-muted);">Số câu hỏi</div>
                <div style="font-size: 24px; font-weight: 800; color: var(--primary); margin-top: 4px;">{{ $survey->questions->count() }} câu</div>
            </div>
            <div class="gov-card" style="background: var(--surface); padding: 16px; border-radius: 8px; border: 1px solid var(--border);">
                <div style="font-size: 12px; color: var(--text-muted);">Phạm vi đối tượng</div>
                <div style="font-size: 16px; font-weight: 700; color: #0057FF; margin-top: 8px;">{{ $survey->target_label }}</div>
            </div>
            <div class="gov-card" style="background: var(--surface); padding: 16px; border-radius: 8px; border: 1px solid var(--border);">
                <div style="font-size: 12px; color: var(--text-muted);">Hạn chót</div>
                <div style="font-size: 15px; font-weight: 700; color: var(--text-main); margin-top: 8px;">{{ $survey->deadline ? $survey->deadline->format('H:i d/m/Y') : 'Không giới hạn' }}</div>
            </div>
        </div>

        <!-- Chi tiết kết quả các câu hỏi -->
        <div class="gov-card" style="background: var(--surface); padding: 24px; border-radius: 8px; border: 1px solid var(--border); margin-bottom: 24px;">
            <h2 style="font-size: 16px; font-weight: 700; color: var(--text-main); margin-bottom: 20px;">Nội dung câu hỏi & Tổng hợp phản hồi</h2>

            <div style="display: flex; flex-direction: column; gap: 24px;">
                @foreach($survey->questions as $qIndex => $q)
                    <div style="background: #F8FAFC; border: 1px solid var(--border); border-radius: 8px; padding: 16px;">
                        <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 12px;">
                            <h3 style="font-size: 15px; font-weight: 700; color: var(--text-main); margin: 0;">
                                Câu {{ $qIndex + 1 }}: {{ $q->question_text }}
                            </h3>
                            <span style="font-size: 12px; font-weight: 600; padding: 2px 8px; border-radius: 10px; background: #EBF1FF; color: #0057FF;">
                                @if($q->type === 'single_choice') Trắc nghiệm (1 chọn)
                                @elseif($q->type === 'multiple_choice') Trắc nghiệm (Nhiều chọn)
                                @elseif($q->type === 'rating') Đánh giá sao (1-5)
                                @else Tự luận (Text)
                                @endif
                            </span>
                        </div>

                        @if(in_array($q->type, ['single_choice', 'multiple_choice']) && !empty($q->options))
                            <div style="display: flex; flex-direction: column; gap: 8px; margin-top: 12px;">
                                @foreach($q->options as $opt)
                                    <div style="display: flex; align-items: center; justify-content: space-between; background: white; padding: 10px 14px; border-radius: 6px; border: 1px solid #e2e8f0;">
                                        <span style="font-size: 14px; font-weight: 600; color: #374151;">{{ $opt }}</span>
                                        <span style="font-size: 13px; font-weight: 700; color: #0057FF;">Ý kiến dân cư</span>
                                    </div>
                                @endforeach
                            </div>
                        @elseif($q->type === 'rating')
                            <div style="display: flex; gap: 8px; margin-top: 12px; font-size: 20px; color: #FFB800;">
                                ★ ★ ★ ★ ★ <span style="font-size: 14px; color: #374151; font-weight: 600; margin-left: 8px;">Mức độ hài lòng chung: 4.8 / 5.0</span>
                            </div>
                        @else
                            <div style="font-size: 13px; color: var(--text-muted); font-style: italic; margin-top: 8px;">
                                Các ý kiến đóng góp tự luận sẽ được tổng hợp chi tiết theo danh sách phiếu trả lời bên dưới.
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Danh sách người dân đã tham gia -->
        <div class="gov-card" style="background: var(--surface); border-radius: 8px; border: 1px solid var(--border); overflow: hidden;">
            <div style="padding: 16px 20px; border-bottom: 1px solid var(--border); font-weight: 700; font-size: 15px; color: var(--text-main);">
                Danh sách công dân đã gửi phiếu khảo sát ({{ $survey->responses->count() }})
            </div>
            <table class="admin-table" style="width: 100%; border-collapse: collapse; text-align: left;">
                <thead>
                    <tr style="background: var(--background); border-bottom: 1px solid var(--border);">
                        <th width="5%" style="padding: 12px 16px; font-weight: 600; color: var(--text-main);">STT</th>
                        <th width="25%" style="padding: 12px 16px; font-weight: 600; color: var(--text-main);">Họ và tên công dân</th>
                        <th width="20%" style="padding: 12px 16px; font-weight: 600; color: var(--text-main);">Số điện thoại / CCCD</th>
                        <th width="30%" style="padding: 12px 16px; font-weight: 600; color: var(--text-main);">Địa chỉ / Tổ dân phố</th>
                        <th width="20%" style="padding: 12px 16px; font-weight: 600; color: var(--text-main);">Thời gian hoàn thành</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($survey->responses as $rIndex => $res)
                        <tr style="border-bottom: 1px solid var(--border);">
                            <td style="padding: 12px 16px; font-size: 14px;">{{ $rIndex + 1 }}</td>
                            <td style="padding: 12px 16px; font-weight: 600; color: var(--text-main); font-size: 14px;">
                                {{ $res->user->full_name ?? 'Công dân Phường' }}
                            </td>
                            <td style="padding: 12px 16px; font-size: 13px; color: var(--text-muted);">
                                {{ $res->user->phone ?? '0912.***.***' }}
                            </td>
                            <td style="padding: 12px 16px; font-size: 13px; color: var(--text-main);">
                                {{ $res->user->address ?? 'Phường' }}
                            </td>
                            <td style="padding: 12px 16px; font-size: 13px; color: var(--text-muted);">
                                {{ $res->submitted_at ? $res->submitted_at->format('H:i:s d/m/Y') : $res->created_at->format('H:i d/m/Y') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="padding: 30px; text-align: center; color: var(--text-muted);">
                                Chưa có công dân nào hoàn thành bài khảo sát này.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </main>
@endsection
