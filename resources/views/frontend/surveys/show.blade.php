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
                @foreach($questionsData as $qIndex => $data)
                    @php
                        $q = $data['question'];
                    @endphp
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

                        @if(in_array($q->type, ['single_choice', 'multiple_choice']))
                            <div style="display: flex; flex-direction: column; gap: 10px; margin-top: 12px;">
                                @forelse($data['option_counts'] as $opt => $count)
                                    @php
                                        $percent = $totalResponses > 0 ? round(($count / $totalResponses) * 100, 1) : 0;
                                    @endphp
                                    <div style="background: white; padding: 12px 14px; border-radius: 6px; border: 1px solid #e2e8f0;">
                                        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 6px;">
                                            <span style="font-size: 14px; font-weight: 600; color: #374151;">{{ $opt }}</span>
                                            <span style="font-size: 13px; font-weight: 700; color: #0057FF;">
                                                {{ $count }} phiếu ({{ $percent }}%)
                                            </span>
                                        </div>
                                        <div style="width: 100%; height: 8px; background: #E2E8F0; border-radius: 4px; overflow: hidden;">
                                            <div style="height: 100%; width: {{ $percent }}%; background: #0057FF; border-radius: 4px; transition: width 0.3s ease;"></div>
                                        </div>
                                    </div>
                                @empty
                                    <div style="font-size: 13px; color: var(--text-muted); font-style: italic;">Chưa có tùy chọn nào.</div>
                                @endforelse
                            </div>
                        @elseif($q->type === 'rating')
                            <div style="display: flex; align-items: center; gap: 12px; margin-top: 12px; background: white; padding: 14px; border-radius: 6px; border: 1px solid #e2e8f0;">
                                <div style="font-size: 24px; color: #FFB800;">
                                    ★ ★ ★ ★ ★
                                </div>
                                <div>
                                    <div style="font-size: 16px; font-weight: 700; color: #1E293B;">
                                        Mức độ hài lòng trung bình: {{ $data['rating_avg'] }} / 5.0
                                    </div>
                                    <div style="font-size: 12px; color: var(--text-muted);">
                                        Dựa trên {{ $data['rating_count'] }} lượt đánh giá thực tế
                                    </div>
                                </div>
                            </div>
                        @else
                            <div style="margin-top: 12px;">
                                @if(count($data['text_answers']) > 0)
                                    <div style="display: flex; flex-direction: column; gap: 8px;">
                                        @foreach($data['text_answers'] as $tAns)
                                            <div style="background: white; border: 1px solid #e2e8f0; padding: 12px 14px; border-radius: 6px;">
                                                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 4px;">
                                                    <span style="font-weight: 600; font-size: 13px; color: var(--primary);">
                                                        <i class="ph ph-user"></i> {{ $tAns['user_name'] }}
                                                    </span>
                                                    <span style="color: var(--text-muted); font-size: 12px;">{{ $tAns['submitted_at'] }}</span>
                                                </div>
                                                <div style="font-size: 14px; color: #374151; font-style: italic;">
                                                    "{{ $tAns['text'] }}"
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <div style="font-size: 13px; color: var(--text-muted); font-style: italic; background: white; padding: 12px; border-radius: 6px; border: 1px solid #e2e8f0;">
                                        Chưa có ý kiến tự luận nào được gửi cho câu hỏi này.
                                    </div>
                                @endif
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
                        <th width="20%" style="padding: 12px 16px; font-weight: 600; color: var(--text-main);">Họ và tên công dân</th>
                        <th width="15%" style="padding: 12px 16px; font-weight: 600; color: var(--text-main);">Số điện thoại</th>
                        <th width="25%" style="padding: 12px 16px; font-weight: 600; color: var(--text-main);">Tổ dân phố</th>
                        <th width="15%" style="padding: 12px 16px; font-weight: 600; color: var(--text-main);">Thời gian gửi</th>
                        <th width="20%" style="padding: 12px 16px; font-weight: 600; color: var(--text-main);">Nội dung phiếu</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($survey->responses as $rIndex => $res)
                        <tr style="border-bottom: 1px solid var(--border);">
                            <td style="padding: 12px 16px; font-size: 14px;">{{ $rIndex + 1 }}</td>
                            <td style="padding: 12px 16px; font-weight: 600; color: var(--text-main); font-size: 14px;">
                                {{ $res->user->full_name ?? ($res->user->name ?? 'Công dân Phường') }}
                            </td>
                            <td style="padding: 12px 16px; font-size: 13px; color: var(--text-muted);">
                                {{ $res->user->phone ?? '0912.***.***' }}
                            </td>
                            <td style="padding: 12px 16px; font-size: 13px; color: var(--text-main);">
                                {{ $res->user->tdp ?? ($res->user->address ?? 'Toàn phường') }}
                            </td>
                            <td style="padding: 12px 16px; font-size: 13px; color: var(--text-muted);">
                                {{ $res->submitted_at ? $res->submitted_at->format('H:i d/m/Y') : $res->created_at->format('H:i d/m/Y') }}
                            </td>
                            <td style="padding: 12px 16px; font-size: 13px;">
                                <details>
                                    <summary style="cursor: pointer; color: #0057FF; font-weight: 600;">Xem câu trả lời</summary>
                                    <div style="margin-top: 8px; padding: 10px; background: #F8FAFC; border-radius: 6px; font-size: 12px;">
                                        @if(is_array($res->answers) && count($res->answers) > 0)
                                            @foreach($res->answers as $ansKey => $ansVal)
                                                <div style="margin-bottom: 6px;">
                                                    <span style="font-weight: 600; color: #333;">Câu/Mục {{ $ansKey }}:</span>
                                                    <span style="color: #555;">{{ is_array($ansVal) ? implode(', ', $ansVal) : $ansVal }}</span>
                                                </div>
                                            @endforeach
                                        @else
                                            <span style="color: #999;">Trống</span>
                                        @endif
                                    </div>
                                </details>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="padding: 30px; text-align: center; color: var(--text-muted);">
                                Chưa có công dân nào hoàn thành bài khảo sát này.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </main>
@endsection
