<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\NewsCategory;
use App\Models\Post;
use App\Models\User;
use Illuminate\Support\Str;

class NewsCategoryAndPostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categoriesData = [
            [
                'name' => 'Tin chính quyền',
                'slug' => 'tin-chinh-quyen',
                'description' => 'Tin chỉ đạo điều hành, hoạt động lãnh đạo UBND phường và HĐND.',
                'order' => 1,
                'is_active' => true,
            ],
            [
                'name' => 'Thông báo dân cư',
                'slug' => 'thong-bao-dan-cu',
                'description' => 'Thông báo về lịch mất nước, điện, tiêm chủng và hoạt động tổ dân phố.',
                'order' => 2,
                'is_active' => true,
            ],
            [
                'name' => 'An ninh trật tự',
                'slug' => 'an-ninh-trat-tu',
                'description' => 'Cảnh báo lừa đảo, công tác phòng chống tội phạm và PCCC trên địa bàn.',
                'order' => 3,
                'is_active' => true,
            ],
            [
                'name' => 'Văn hóa - Xã hội',
                'slug' => 'van-hoa-xa-hoi',
                'description' => 'Phong trào thể thao, gia đình văn hóa, các lễ hội và hoạt động cộng đồng.',
                'order' => 4,
                'is_active' => true,
            ],
            [
                'name' => 'Cải cách hành chính',
                'slug' => 'cai-cach-hanh-chinh',
                'description' => 'Hướng dẫn thủ tục dịch vụ công trực tuyến, căn cước công dân, VNeID.',
                'order' => 5,
                'is_active' => true,
            ],
        ];

        $createdCategories = [];
        foreach ($categoriesData as $cat) {
            $createdCategories[] = NewsCategory::updateOrCreate(
                ['slug' => $cat['slug']],
                $cat
            );
        }

        $adminUser = User::first() ?? User::create([
            'name' => 'Quản trị viên',
            'email' => 'admin@phuong.gov.vn',
            'password' => bcrypt('12345678'),
        ]);

        $postsData = [
            [
                'title' => 'UBND Phường triển khai chiến dịch làm sạch dữ liệu đất đai và cấp căn cước công dân',
                'summary' => 'Sáng ngày 04/08, UBND phường phát động chiến dịch phối hợp cùng Công an phường rà soát dữ liệu đất đai và cấp CCCD gắn chip lưu động cho người dân.',
                'content' => "Sáng ngày 04/08, UBND phường đã chính thức phát động chiến dịch đợt 1 về việc làm sạch dữ liệu đất đai kết hợp cấp Căn cước công dân gắn chip và kích hoạt tài khoản định danh điện tử VNeID mức 2 cho nhân dân trên địa bàn.\n\nĐồng chí Chủ tịch UBND phường nhấn mạnh đây là nhiệm vụ trọng tâm nhằm đẩy mạnh chuyển đổi số quốc gia, tạo điều kiện thuận lợi nhất cho người dân khi thực hiện các giao dịch hành chính công.\n\nLịch cấp CCCD lưu động sẽ được triển khai tại Nhà văn hóa các Tổ dân phố từ ngày 05/08 đến hết ngày 20/08/2026. Trân trọng kính mời bà con nhân dân thu xếp thời gian tham gia.",
                'category' => 'news',
                'news_category_id' => $createdCategories[0]->id,
                'image' => 'https://images.unsplash.com/photo-1577495508048-b635879837f1?auto=format&fit=crop&w=800&q=80',
                'status' => 'published',
                'published_at' => now()->subHours(2),
                'views_count' => 142,
                'is_featured' => true,
            ],
            [
                'title' => 'Thông báo tạm ngưng cấp nước sinh hoạt khu vực Tổ dân phố 3 và Tổ dân phố 4 ngày 06/08',
                'summary' => 'Công ty Cấp nước thông báo tạm ngưng cung cấp nước sinh hoạt từ 08h00 đến 17h00 ngày 06/08 để đầu nối tuyến ống đường đường chính.',
                'content' => "UBND Phường xin thông báo tới toàn thể bà con nhân dân đang cư trú tại khu vực Tổ dân phố 3 và Tổ dân phố 4:\n\nDo kế hoạch thi công nâng cấp, đấu nối mạng lưới cấp nước sinh hoạt trọng điểm, Công ty Cấp nước địa phương sẽ tạm ngừng cung cấp nước sạch vào:\n\n- Thời gian: Từ 08h00 đến 17h00 ngày 06/08/2026.\n- Phạm vi ảnh hưởng: Toàn bộ hộ dân thuộc Tổ 3 và Tổ 4.\n\nKính đề nghị bà con chủ động trữ nước sạch để sinh hoạt và sản xuất. Sau 17h00 cùng ngày, nguồn nước sẽ được mở lại bình thường.",
                'category' => 'announcement',
                'news_category_id' => $createdCategories[1]->id,
                'image' => 'https://images.unsplash.com/photo-1541888946425-d0fbb186a5b7?auto=format&fit=crop&w=800&q=80',
                'status' => 'published',
                'published_at' => now()->subHours(8),
                'views_count' => 310,
                'is_featured' => true,
            ],
            [
                'title' => 'Cảnh báo hình thức lừa đảo giả danh cán bộ Thuế và Bảo hiểm xã hội qua điện thoại',
                'summary' => 'Công an phường phát thông báo cảnh báo về chiêu trò lừa đảo giả danh cán bộ nhà nước yêu cầu cài đặt ứng dụng lạ để kê khai thông tin.',
                'content' => "Thời gian gần đây, Công an phường tiếp nhận nhiều phản ánh của người dân về việc một số đối tượng giả danh cán bộ Chi cục Thuế hoặc Bảo hiểm xã hội phường gọi điện thoại yêu cầu người dân cài đặt ứng dụng giả mạo (.apk) để hoàn thuế hoặc cập nhật thông tin thẻ BHXH.\n\nCông an phường khuyến cáo người dân:\n1. Tuyệt đối không bấm vào các đường link lạ gửi qua Zalo, SMS.\n2. Không tải các tệp tin đuôi .apk ngoài ứng dụng Google Play hoặc App Store.\n3. Cơ quan nhà nước KHÔNG làm việc, yêu cầu chuyển tiền hoặc hướng dẫn cài phần mềm qua điện thoại.\n\nKhi phát hiện nghi vấn, đề nghị liên hệ ngay Hotline Công an phường: 024.3825.xxxx để được hỗ trợ kịp thời.",
                'category' => 'news',
                'news_category_id' => $createdCategories[2]->id,
                'image' => 'https://images.unsplash.com/photo-1563986768609-322da13575f3?auto=format&fit=crop&w=800&q=80',
                'status' => 'published',
                'published_at' => now()->subDays(1),
                'views_count' => 520,
                'is_featured' => false,
            ],
            [
                'title' => 'Khai mạc Giải bóng đá nam phong trào chào mừng Ngày Hội toàn dân bảo vệ an ninh Tổ quốc',
                'summary' => 'Giải bóng đá thu hút 8 đội bóng đại diện cho các Tổ dân phố tham gia tranh tài sôi nổi tại Sân vận động phường.',
                'content' => "Chiều ngày 03/08, Đoàn thanh niên phối hợp cùng Hội LHTN phường đã tổ chức Lễ khai mạc Giải bóng đá phong trào mừng Ngày hội toàn dân bảo vệ an ninh Tổ quốc.\n\nGiải đấu năm nay có sự góp mặt của 8 đội bóng đại diện cho 8 Tổ dân phố trên địa bàn. Các trận đấu hứa hẹn sẽ mang đến những pha bóng đẹp mắt và tinh thần đoàn kết cao giữa nhân dân các khu dân cư.\n\nGiải đấu kéo dài từ ngày 03/08 đến ngày 10/08/2026. Kính mời toàn thể nhân dân đến xem và cổ vũ cho các đội bóng!",
                'category' => 'news',
                'news_category_id' => $createdCategories[3]->id,
                'image' => 'https://images.unsplash.com/photo-1508098682722-e99c43a406b2?auto=format&fit=crop&w=800&q=80',
                'status' => 'published',
                'published_at' => now()->subDays(2),
                'views_count' => 195,
                'is_featured' => false,
            ],
            [
                'title' => 'Hướng dẫn quy trình nộp hồ sơ Đăng ký kết hôn trực tuyến trên Cổng dịch vụ công Quốc gia',
                'summary' => 'Chi tiết các bước chuẩn bị giấy tờ, scan tài liệu và gửi hồ sơ trực tuyến giúp tiết kiệm 80% thời gian đi lại.',
                'content' => "Nhằm tạo điều kiện thuận lợi cho công dân, Bộ phận Một cửa UBND phường xin hướng dẫn chi tiết quy trình đăng ký kết hôn trực tuyến:\n\nBước 1: Truy cập Cổng dịch vụ công Quốc gia (dichvucong.gov.vn) hoặc ứng dụng Zalo Mini App eWard.\nBước 2: Đăng nhập bằng tài khoản VNeID mức 2.\nBước 3: Chọn thủ tục 'Đăng ký kết hôn', chọn cơ quan giải quyết là UBND Phường.\nBước 4: Tải lên các giấy tờ gồm: Giấy xác nhận tình trạng hôn nhân, Căn cước công dân 2 bên.\nBước 5: Nộp hồ sơ và theo dõi tiến độ xử lý trực tiếp trên ứng dụng.\n\nKhi hồ sơ hợp lệ, hai bên nam nữ chỉ cần mang bản gốc đến Bộ phận 1 cửa để ký tên và nhận Giấy chứng nhận kết hôn.",
                'category' => 'policy',
                'news_category_id' => $createdCategories[4]->id,
                'image' => 'https://images.unsplash.com/photo-1450133064473-71024230f91b?auto=format&fit=crop&w=800&q=80',
                'status' => 'published',
                'published_at' => now()->subDays(3),
                'views_count' => 388,
                'is_featured' => true,
            ]
        ];

        foreach ($postsData as $pData) {
            Post::create(array_merge($pData, [
                'author_id' => $adminUser->id,
            ]));
        }
    }
}
