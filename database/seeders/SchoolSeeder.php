<?php

namespace Database\Seeders;

use App\Models\School;
use Illuminate\Database\Seeder;

class SchoolSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $schools = [
            [
                'name' => 'Trường Mầm non Hoa Sen',
                'level' => 'kindergarten',
                'address' => '12 Trần Hưng Đạo, Phường Nguyễn Trãi, Hà Đông, Hà Nội',
                'phone' => '02438254111',
                'email' => 'mnhoasen@hadong.edu.vn',
                'website' => 'http://mnhoasen.hadong.edu.vn',
                'latitude' => 20.97230000,
                'longitude' => 105.77420000,
                'description' => 'Trường mầm non đạt chuẩn quốc gia cấp độ 2, môi trường giáo dục thân thiện, an toàn và phát triển toàn diện cho trẻ từ 18 tháng đến 5 tuổi.',
                'image' => null,
            ],
            [
                'name' => 'Trường Tiểu học Nguyễn Huệ',
                'level' => 'primary',
                'address' => '45 Lê Lợi, Phường Nguyễn Trãi, Hà Đông, Hà Nội',
                'phone' => '02438254222',
                'email' => 'thnguyenhue@hadong.edu.vn',
                'website' => 'http://thnguyenhue.hadong.edu.vn',
                'latitude' => 20.97010000,
                'longitude' => 105.77610000,
                'description' => 'Trường tiểu học công lập chất lượng cao với đội ngũ giáo viên giàu kinh nghiệm, đạt nhiều thành tích xuất sắc trong phong trào dạy và học tại quận Hà Đông.',
                'image' => null,
            ],
            [
                'name' => 'Trường THCS Quang Trung',
                'level' => 'secondary',
                'address' => '89 Nguyễn Trãi, Phường Nguyễn Trãi, Hà Đông, Hà Nội',
                'phone' => '02438254333',
                'email' => 'thcsquangtrung@hadong.edu.vn',
                'website' => 'http://thcsquangtrung.hadong.edu.vn',
                'latitude' => 20.96850000,
                'longitude' => 105.77120000,
                'description' => 'Môi trường giáo dục năng động, sáng tạo, trang thiết bị hiện đại cùng chương trình phát triển năng khiếu đa dạng dành cho học sinh THCS.',
                'image' => null,
            ],
            [
                'name' => 'Trường THPT chuyên Nguyễn Huệ',
                'level' => 'high_school',
                'address' => '150 Quang Trung, Phường La Khê, Hà Đông, Hà Nội',
                'phone' => '02438254444',
                'email' => 'c3nguyenhue@hanoi.edu.vn',
                'website' => 'http://chuyennguyenhue.edu.vn',
                'latitude' => 20.96210000,
                'longitude' => 105.76540000,
                'description' => 'Một trong ba trường THPT chuyên hàng đầu của thành phố Hà Nội, giàu truyền thống học sinh giỏi quốc gia và quốc tế.',
                'image' => null,
            ],
            [
                'name' => 'Trường Tiểu học và THCS Ngôi Sao Hà Nội - Hoàng Mai',
                'level' => 'other',
                'address' => 'Lô T1, KĐT Trung Hòa Nhân Chính, Thanh Xuân, Hà Nội',
                'phone' => '02438254555',
                'email' => 'info@ngoisaohanoi.edu.vn',
                'website' => 'http://ngoisaohanoi.edu.vn',
                'latitude' => 21.00650000,
                'longitude' => 105.80120000,
                'description' => 'Hệ thống trường liên cấp chất lượng cao hướng tới phát triển toàn diện tư duy toán học, tiếng Anh và kỹ năng sống toàn cầu.',
                'image' => null,
            ],
        ];

        foreach ($schools as $school) {
            School::create($school);
        }
    }
}
