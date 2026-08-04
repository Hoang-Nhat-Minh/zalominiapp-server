@extends('layouts.main')

@section('title', 'Tạo bài khảo sát dân cư mới - Hệ thống Quản Trị')

@section('content')
    <main class="admin-content-wrapper surveys-wrapper">
        <div class="surveys-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
            <div>
                <h1 style="font-size: 24px; font-weight: 600; color: var(--text-main); margin-bottom: 4px;">Thiết kế phiếu khảo sát dân cư (Biểu mẫu động)</h1>
                <p style="color: var(--text-muted); font-size: 14px;">Tạo câu hỏi trắc nghiệm, tự luận hoặc đánh giá sao phát hành tới người dân.</p>
            </div>
            <a href="{{ route('surveys.index') }}" class="admin-btn" style="display: inline-flex; align-items: center; gap: 8px; text-decoration: none; padding: 10px 16px; border-radius: 6px; background: #e0e0e0; color: #333; font-weight: 600; font-size: 14px;">
                <i class="ph ph-arrow-left"></i> Quay lại
            </a>
        </div>

        @if ($errors->any())
            <div style="padding: 12px 16px; background-color: #FEF2F2; border: 1px solid #EF4444; color: #991B1B; border-radius: 6px; margin-bottom: 20px;">
                <ul style="margin: 0; padding-left: 20px;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('surveys.store') }}" method="POST" id="surveyForm">
            @csrf
            <!-- Thông tin chung -->
            <div class="gov-card" style="background: var(--surface); padding: 24px; border-radius: 8px; border: 1px solid var(--border); margin-bottom: 24px;">
                <h2 style="font-size: 16px; font-weight: 700; color: var(--text-main); margin-bottom: 16px; display: flex; align-items: center; gap: 8px;">
                    <i class="ph ph-info" style="color: var(--primary);"></i> 1. Thông tin chung khảo sát
                </h2>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                    <div style="grid-column: span 2;">
                        <label style="display: block; font-weight: 600; margin-bottom: 6px; font-size: 14px;">Tiêu đề phiếu khảo sát <span style="color: red;">*</span></label>
                        <input type="text" name="title" value="{{ old('title') }}" placeholder="Ví dụ: Khảo sát ý kiến nhân dân về kế hoạch nâng cấp hạ tầng đô thị Quý 3..." required style="width: 100%; padding: 10px 12px; border: 1px solid var(--border); border-radius: 6px; font-size: 14px;">
                    </div>

                    <div>
                        <label style="display: block; font-weight: 600; margin-bottom: 6px; font-size: 14px;">Phạm vi áp dụng <span style="color: red;">*</span></label>
                        <select name="target_tdp" required style="width: 100%; padding: 10px 12px; border: 1px solid var(--border); border-radius: 6px; font-size: 14px; background: white;">
                            <option value="all">Toàn phường</option>
                            <option value="Tổ dân phố 1">Tổ dân phố 1</option>
                            <option value="Tổ dân phố 2">Tổ dân phố 2</option>
                            <option value="Tổ dân phố 3">Tổ dân phố 3</option>
                            <option value="Tổ dân phố 4">Tổ dân phố 4</option>
                            <option value="Tổ dân phố 5">Tổ dân phố 5</option>
                        </select>
                    </div>

                    <div>
                        <label style="display: block; font-weight: 600; margin-bottom: 6px; font-size: 14px;">Hạn chót gửi phản hồi</label>
                        <input type="datetime-local" name="deadline" value="{{ old('deadline') }}" style="width: 100%; padding: 10px 12px; border: 1px solid var(--border); border-radius: 6px; font-size: 14px;">
                    </div>

                    <div style="grid-column: span 2;">
                        <label style="display: block; font-weight: 600; margin-bottom: 6px; font-size: 14px;">Mô tả chi tiết / Mục đích khảo sát</label>
                        <textarea name="description" rows="3" placeholder="Nhập mục đích khảo sát, khuyến khích công dân tham gia..." style="width: 100%; padding: 10px 12px; border: 1px solid var(--border); border-radius: 6px; font-size: 14px;">{{ old('description') }}</textarea>
                    </div>

                    <div style="display: flex; align-items: center; grid-column: span 2;">
                        <label style="display: flex; align-items: center; gap: 8px; font-weight: 600; font-size: 14px; cursor: pointer;">
                            <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }} style="width: 18px; height: 18px;">
                            Phát hành ngay lên Zalo Mini App dành cho công dân
                        </label>
                    </div>
                </div>
            </div>

            <!-- Danh sách câu hỏi động -->
            <div class="gov-card" style="background: var(--surface); padding: 24px; border-radius: 8px; border: 1px solid var(--border); margin-bottom: 24px;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
                    <h2 style="font-size: 16px; font-weight: 700; color: var(--text-main); margin: 0; display: flex; align-items: center; gap: 8px;">
                        <i class="ph ph-list-numbers" style="color: var(--primary);"></i> 2. Nội dung các câu hỏi
                    </h2>
                    <button type="button" id="addQuestionBtn" class="admin-btn" style="background: #E8F0FE; color: #0057FF; border: none; font-weight: 600; padding: 8px 14px; border-radius: 6px; cursor: pointer; display: flex; align-items: center; gap: 6px;">
                        <i class="ph ph-plus"></i> Thêm câu hỏi
                    </button>
                </div>

                <div id="questionsContainer" style="display: flex; flexDirection: column; gap: 20px;">
                    <!-- Sẽ render bằng JS -->
                </div>
            </div>

            <div style="display: flex; gap: 12px; justify-content: flex-end;">
                <a href="{{ route('surveys.index') }}" class="admin-btn" style="padding: 12px 24px; border-radius: 6px; background: #e0e0e0; color: #333; text-decoration: none; font-weight: 600;">Hủy bỏ</a>
                <button type="submit" class="admin-btn admin-btn-primary" style="padding: 12px 28px; border-radius: 6px; font-weight: 600; border: none; cursor: pointer;">Phát hành bài Khảo sát</button>
            </div>
        </form>
    </main>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            let questionCount = 0;
            const container = document.getElementById('questionsContainer');
            const addBtn = document.getElementById('addQuestionBtn');

            function addQuestion(defaultType = 'single_choice', defaultText = '', defaultOpts = '') {
                questionCount++;
                const index = questionCount - 1;

                const qCard = document.createElement('div');
                qCard.className = 'question-card';
                qCard.style.cssText = 'background: #F8FAFC; border: 1px solid var(--border); border-radius: 8px; padding: 16px; position: relative;';

                qCard.innerHTML = `
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px;">
                        <span style="font-weight: 700; color: var(--primary); font-size: 14px;">Câu hỏi ${questionCount}</span>
                        <button type="button" class="remove-q-btn" style="background: none; border: none; color: #EF4444; cursor: pointer; font-size: 14px; font-weight: 600; display: flex; align-items: center; gap: 4px;">
                            <i class="ph ph-trash"></i> Xóa câu hỏi
                        </button>
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 200px; gap: 12px; margin-bottom: 12px;">
                        <div>
                            <input type="text" name="questions[${index}][question_text]" value="${defaultText}" placeholder="Nhập nội dung câu hỏi..." required style="width: 100%; padding: 8px 12px; border: 1px solid var(--border); border-radius: 6px; font-size: 14px; background: white;">
                        </div>
                        <div>
                            <select name="questions[${index}][type]" class="q-type-select" style="width: 100%; padding: 8px 12px; border: 1px solid var(--border); border-radius: 6px; font-size: 14px; background: white;">
                                <option value="single_choice" ${defaultType==='single_choice'?'selected':''}>Trắc nghiệm (Chỉ chọn 1)</option>
                                <option value="multiple_choice" ${defaultType==='multiple_choice'?'selected':''}>Trắc nghiệm (Chọn nhiều)</option>
                                <option value="rating" ${defaultType==='rating'?'selected':''}>Đánh giá mức độ (1-5 Sao)</option>
                                <option value="text" ${defaultType==='text'?'selected':''}>Ý kiến tự luận (Text)</option>
                            </select>
                        </div>
                    </div>

                    <div class="options-box" style="margin-bottom: 12px; display: ${['single_choice', 'multiple_choice'].includes(defaultType) ? 'block' : 'none'};">
                        <label style="display: block; font-size: 12px; font-weight: 600; color: var(--text-muted); margin-bottom: 4px;">Các tùy chọn đáp án (Mỗi tùy chọn nhập 1 dòng):</label>
                        <textarea name="questions[${index}][options]" rows="3" placeholder="Đáp án 1&#10;Đáp án 2&#10;Đáp án 3..." style="width: 100%; padding: 8px 12px; border: 1px solid var(--border); border-radius: 6px; font-size: 13px; background: white;">${defaultOpts}</textarea>
                    </div>

                    <div style="display: flex; align-items: center; gap: 8px;">
                        <input type="checkbox" name="questions[${index}][is_required]" value="1" checked id="req_${index}">
                        <label for="req_${index}" style="font-size: 13px; font-weight: 600; color: var(--text-muted); cursor: pointer;">Bắt buộc công dân phải trả lời câu hỏi này</label>
                    </div>
                `;

                container.appendChild(qCard);

                // Handle type change
                const typeSelect = qCard.querySelector('.q-type-select');
                const optionsBox = qCard.querySelector('.options-box');

                typeSelect.addEventListener('change', (e) => {
                    const val = e.target.value;
                    if (val === 'single_choice' || val === 'multiple_choice') {
                        optionsBox.style.display = 'block';
                    } else {
                        optionsBox.style.display = 'none';
                    }
                });

                // Remove question
                qCard.querySelector('.remove-q-btn').addEventListener('click', () => {
                    qCard.remove();
                });
            }

            // Default 2 questions
            addQuestion('single_choice', 'Bạn đánh giá thế nào về tiến độ cải tạo vỉa hè tuyến đường chính?', 'Rất hài lòng\nHài lòng\nChưa hài lòng\nKhông hài lòng');
            addQuestion('rating', 'Đánh giá mức độ hài lòng về chất lượng phục vụ của cán bộ bộ phận Một cửa:');

            addBtn.addEventListener('click', () => {
                addQuestion();
            });
        });
    </script>
    @endpush
@endsection
