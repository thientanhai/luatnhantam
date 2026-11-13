-- ============================================================================
-- WebChiaSe.vn Sample Data SQL Dump
-- Table prefix: wpig_ (khớp với wp-config.php)
-- Bao gồm:
-- 1. 7 Chuyên mục chính (Luật pháp, Công nghệ - IT, Kinh doanh, Tài chính, Đời sống, Kiến thức, Tin tức)
-- 2. 14 Bài viết mẫu (khớp thiết kế Trang chủ, kèm lượt xem post_views)
-- 3. Trang tĩnh "Trang chủ" (Page - Full Width + UX Builder Shortcode) & "Liên hệ"
-- ============================================================================

SET NAMES utf8mb4;

-- 1. 7 CHUYÊN MỤC CHÍNH (wpig_terms & wpig_term_taxonomy)
INSERT INTO `wpig_terms` (`term_id`, `name`, `slug`, `term_group`) VALUES
(101, 'Luật pháp', 'phap-luat', 0),
(102, 'Công nghệ - IT', 'cong-nghe-it', 0),
(103, 'Kinh doanh', 'kinh-doanh', 0),
(104, 'Tài chính', 'tai-chinh', 0),
(105, 'Đời sống', 'doi-song', 0),
(106, 'Kiến thức', 'kien-thuc', 0),
(107, 'Tin tức', 'tin-tuc', 0)
ON DUPLICATE KEY UPDATE `name` = VALUES(`name`), `slug` = VALUES(`slug`);

INSERT INTO `wpig_term_taxonomy` (`term_taxonomy_id`, `term_id`, `taxonomy`, `description`, `parent`, `count`) VALUES
(101, 101, 'category', 'Tư vấn pháp luật, hỏi đáp quy định lao động, dân sự, doanh nghiệp và hành chính mới nhất.', 0, 6),
(102, 102, 'category', 'Thủ thuật máy tính, phần mềm, bảo mật thông tin, đánh giá thiết bị và xu hướng chuyển đổi số.', 0, 4),
(103, 103, 'category', 'Kiến thức khởi nghiệp, quản trị doanh nghiệp, bán hàng online và phát triển thương hiệu.', 0, 1),
(104, 104, 'category', 'Quản lý tài chính cá nhân, đầu tư chứng khoán, bất động sản và dịch vụ ngân hàng.', 0, 1),
(105, 105, 'category', 'Chăm sóc sức khỏe, dinh dưỡng, gia đình và kỹ năng cân bằng cuộc sống hiện đại.', 0, 1),
(106, 106, 'category', 'Học tập, phát triển bản thân, ngoại ngữ, kỹ năng mềm và tri thức tổng hợp.', 0, 1),
(107, 107, 'category', 'Tổng hợp tin tức kinh tế, xã hội trong nước và quốc tế cập nhật mỗi ngày.', 0, 0)
ON DUPLICATE KEY UPDATE `description` = VALUES(`description`), `count` = VALUES(`count`);

-- 2. 14 BÀI VIẾT MẪU & 2 TRANG TĨNH (wpig_posts)
INSERT INTO `wpig_posts` (
	`ID`, `post_author`, `post_date`, `post_date_gmt`, `post_content`, `post_title`, `post_excerpt`,
	`post_status`, `comment_status`, `ping_status`, `post_name`, `to_ping`, `pinged`,
	`post_modified`, `post_modified_gmt`, `post_content_filtered`, `post_parent`, `guid`,
	`menu_order`, `post_type`, `post_mime_type`, `comment_count`
) VALUES
(1001, 1, '2025-10-03 21:00:00', '2025-10-03 14:00:00',
'<p><strong>Người lao động cần nắm rõ quyền lợi và nghĩa vụ của mình để bảo vệ bản thân, tránh những rủi ro pháp lý không đáng có trong quan hệ lao động.</strong></p><h2>1. Quyền lợi cơ bản của người lao động</h2><p>Theo Bộ luật Lao động hiện hành, người lao động được làm việc trong môi trường an toàn, hưởng lương tương xứng với trình độ và được tham gia đầy đủ Bảo hiểm xã hội, Bảo hiểm y tế, Bảo hiểm thất nghiệp.</p><blockquote>Bài viết được đội ngũ chuyên gia pháp lý của WebChiaSe.vn tổng hợp và kiểm duyệt kỹ lưỡng.</blockquote><h2>2. Nghĩa vụ thực hiện hợp đồng lao động</h2><ul><li>Hoàn thành công việc đã cam kết trong hợp đồng lao động.</li><li>Chấp hành kỷ luật lao động, nội quy doanh nghiệp và quy định an toàn vệ sinh lao động.</li></ul>',
'Những điều cần biết về quyền và nghĩa vụ của người lao động',
'Người lao động cần nắm rõ quyền lợi và nghĩa vụ của mình để bảo vệ bản thân, tránh những rủi ro pháp lý không đáng có.',
'publish', 'open', 'open', 'nhung-dieu-can-biet-ve-quyen-va-nghia-vu-cua-nguoi-lao-dong', '', '',
'2025-10-03 21:00:00', '2025-10-03 14:00:00', '', 0, '/?p=1001', 0, 'post', '', 12),

(1002, 1, '2025-10-02 09:15:00', '2025-10-02 02:15:00',
'<p><strong>Quy trình chuẩn bị hồ sơ và nộp đăng ký hộ kinh doanh, doanh nghiệp trực tuyến qua Cổng thông tin quốc gia nhanh chóng và hợp lệ.</strong></p><h2>1. Chuẩn bị chữ ký số hoặc tài khoản đăng ký kinh doanh</h2><p>Người nộp hồ sơ cần tạo tài khoản trên Cổng thông tin quốc gia về đăng ký doanh nghiệp và xác thực điện tử.</p><h2>2. Các bước nộp hồ sơ online</h2><ul><li>Bước 1: Chọn loại hình doanh nghiệp hoặc hộ kinh doanh.</li><li>Bước 2: Điền thông tin tên công ty, địa chỉ, vốn điều lệ, ngành nghề kinh doanh.</li><li>Bước 3: Đính kèm văn bản điện tử, ký xác thực và nhận Giấy chứng nhận sau 3 ngày làm việc.</li></ul>',
'Hướng dẫn làm thủ tục đăng ký kinh doanh online',
'Quy trình chuẩn bị hồ sơ và nộp đăng ký hộ kinh doanh, doanh nghiệp trực tuyến qua Cổng thông tin quốc gia nhanh chóng và hợp lệ.',
'publish', 'open', 'open', 'huong-dan-lam-thu-tuc-dang-ky-kinh-doanh-online', '', '',
'2025-10-02 09:15:00', '2025-10-02 02:15:00', '', 0, '/?p=1002', 0, 'post', '', 18),

(1003, 1, '2025-10-01 10:00:00', '2025-10-01 03:00:00',
'<p><strong>Tổng hợp danh mục các đối tượng và vụ việc dân sự, hành chính được miễn hoặc giảm tạm ứng án phí, án phí Tòa án theo quy định hiện hành.</strong></p><h2>1. Các trường hợp được miễn toàn bộ án phí</h2><p>Người lao động khởi kiện đòi tiền lương, trợ cấp thôi việc, bảo hiểm xã hội; người cao tuổi, người khuyết tật hoặc hộ nghèo theo quy định tại Nghị quyết 326/2016/UBTVQH14.</p>',
'Các trường hợp được miễn án phí khi khởi kiện',
'Tổng hợp danh mục các đối tượng và vụ việc dân sự, hành chính được miễn hoặc giảm tạm ứng án phí, án phí Tòa án theo quy định hiện hành.',
'publish', 'open', 'open', 'cac-truong-hop-duoc-mien-an-phi-khi-khoi-kien', '', '',
'2025-10-01 10:00:00', '2025-10-01 03:00:00', '', 0, '/?p=1003', 0, 'post', '', 14),

(1004, 1, '2025-09-30 14:20:00', '2025-09-30 07:20:00',
'<p><strong>So sánh chi tiết bản chất pháp lý, quyền lợi bảo hiểm xã hội, thuế thu nhập và trách nhiệm của các bên trong hai loại hợp đồng phổ biến.</strong></p><h2>1. Tiêu chí nhận diện Hợp đồng lao động</h2><p>Có sự quản lý, điều hành, giám sát của người sử dụng lao động và trả lương theo định kỳ.</p>',
'Phân biệt hợp đồng lao động và hợp đồng dịch vụ',
'So sánh chi tiết bản chất pháp lý, quyền lợi bảo hiểm xã hội, thuế thu nhập và trách nhiệm của các bên trong hai loại hợp đồng phổ biến.',
'publish', 'open', 'open', 'phan-biet-hop-dong-lao-dong-va-hop-dong-dich-vu', '', '',
'2025-09-30 14:20:00', '2025-09-30 07:20:00', '', 0, '/?p=1004', 0, 'post', '', 22),

(1005, 1, '2025-09-28 16:00:00', '2025-09-28 09:00:00',
'<p><strong>Cập nhật mức phạt tiền và hệ thống trừ điểm giấy phép lái xe đối với các hành vi vi phạm trật tự an toàn giao thông đường bộ năm 2025.</strong></p><h2>1. Quy định về 12 điểm giấy phép lái xe</h2><p>Mỗi giấy phép lái xe có 12 điểm/năm; tùy mức độ vi phạm sẽ bị trừ từ 2 đến 12 điểm.</p>',
'Quy định mới về xử phạt vi phạm giao thông năm 2025',
'Cập nhật mức phạt tiền và hệ thống trừ điểm giấy phép lái xe đối với các hành vi vi phạm trật tự an toàn giao thông đường bộ.',
'publish', 'open', 'open', 'quy-dinh-moi-ve-xu-phat-vi-pham-giao-thong-nam-2025', '', '',
'2025-09-28 16:00:00', '2025-09-28 09:00:00', '', 0, '/?p=1005', 0, 'post', '', 31),

(1006, 1, '2025-09-25 11:00:00', '2025-09-25 04:00:00',
'<p><strong>Hướng dẫn chi tiết hồ sơ, thẩm quyền Tòa án giải quyết, thời gian thụ lý và quy định chia tài sản, quyền nuôi con khi ly hôn đơn phương.</strong></p><h2>1. Hồ sơ cần chuẩn bị</h2><ul><li>Đơn khởi kiện ly hôn đơn phương theo mẫu chuẩn.</li><li>Bản chính Giấy chứng nhận kết hôn, bản sao CCCD và Giấy khai sinh của các con.</li></ul>',
'Thủ tục ly hôn đơn phương mới nhất năm 2025',
'Hướng dẫn chi tiết hồ sơ, thẩm quyền Tòa án giải quyết, thời gian thụ lý và quy định chia tài sản, quyền nuôi con khi ly hôn đơn phương.',
'publish', 'open', 'open', 'thu-tuc-ly-hon-don-phuong-moi-nhat-nam-2025', '', '',
'2025-09-25 11:00:00', '2025-09-25 04:00:00', '', 0, '/?p=1006', 0, 'post', '', 12),

(1007, 1, '2025-10-02 21:00:00', '2025-10-02 14:00:00',
'<p><strong>Bài viết hướng dẫn từng bước cài đặt Windows 11 một cách dễ hiểu, phù hợp cho cả người mới bắt đầu mà không làm mất dữ liệu.</strong></p><h2>1. Kiểm tra cấu hình phần cứng (TPM 2.0 & Secure Boot)</h2><p>Đảm bảo máy tính có tối thiểu 4GB RAM, 64GB bộ nhớ trống và bật TPM 2.0 trong BIOS.</p><h2>2. Các bước nâng cấp hoặc cài mới bằng USB Boot</h2><ul><li>Tải bộ cài ISO chính thức từ Microsoft.</li><li>Tạo USB cài đặt bằng Rufus và tiến hành thiết lập theo hướng dẫn trên màn hình.</li></ul>',
'Hướng dẫn cài đặt Windows 11 chi tiết từ A-Z',
'Bài viết hướng dẫn từng bước cài đặt Windows 11 một cách dễ hiểu, phù hợp cho cả người mới bắt đầu.',
'publish', 'open', 'open', 'huong-dan-cai-dat-windows-11-chi-tiet-tu-a-z', '', '',
'2025-10-02 21:00:00', '2025-10-02 14:00:00', '', 0, '/?p=1007', 0, 'post', '', 18),

(1008, 1, '2025-10-02 10:30:00', '2025-10-02 03:30:00',
'<p><strong>Thiết lập xác thực 2 yếu tố (2FA), cảnh báo đăng nhập lạ và nhận diện các đường link giả mạo đánh cắp thông tin tài khoản Facebook.</strong></p><h2>1. Bật xác thực 2 yếu tố qua ứng dụng Authenticator</h2><p>Sử dụng Google Authenticator hoặc khóa bảo mật vật lý thay vì chỉ dùng tin nhắn SMS.</p>',
'Cách bảo vệ tài khoản Facebook khỏi bị hack',
'Thiết lập xác thực 2 yếu tố (2FA), cảnh báo đăng nhập lạ và nhận diện các đường link giả mạo đánh cắp thông tin tài khoản.',
'publish', 'open', 'open', 'cach-bao-ve-tai-khoan-facebook-khoi-bi-hack', '', '',
'2025-10-02 10:30:00', '2025-10-02 03:30:00', '', 0, '/?p=1008', 0, 'post', '', 25),

(1009, 1, '2025-09-29 09:45:00', '2025-09-29 02:45:00',
'<p><strong>Checklist cấu trúc on-page, tốc độ tải trang, nghiên cứu từ khóa và xây dựng nội dung chuẩn EEAT giúp website lên top Google bền vững.</strong></p><h2>1. Tối ưu kỹ thuật (Technical SEO)</h2><p>Khai báo Google Search Console, tạo Sitemap XML, cấu hình HTTPS và tối ưu Core Web Vitals.</p>',
'Tối ưu SEO cho website mới: 10 bước quan trọng',
'Checklist cấu trúc on-page, tốc độ tải trang, nghiên cứu từ khóa và xây dựng nội dung chuẩn EEAT giúp website lên top Google bền vững.',
'publish', 'open', 'open', 'toi-uu-seo-cho-website-moi-10-buoc-quan-trong', '', '',
'2025-09-29 09:45:00', '2025-09-29 02:45:00', '', 0, '/?p=1009', 0, 'post', '', 37),

(1010, 1, '2025-09-20 18:00:00', '2025-09-20 11:00:00',
'<p><strong>Tư vấn tiêu chí chọn mua laptop theo từng ngành học Kinh tế, Kỹ thuật, Đồ họa với độ bền cao và thời lượng pin ấn tượng.</strong></p><h2>1. Chọn cấu hình theo khối ngành</h2><p>Sinh viên khối Kinh tế ưu tiên mỏng nhẹ, pin 8-10 tiếng; sinh viên IT và Đồ họa ưu tiên RAM từ 16GB và màn hình chuẩn màu 100% sRGB.</p>',
'Laptop cho sinh viên: Nên chọn hãng nào tốt nhất?',
'Tư vấn tiêu chí chọn mua laptop theo từng ngành học Kinh tế, Kỹ thuật, Đồ họa với độ bền cao và thời lượng pin ấn tượng.',
'publish', 'open', 'open', 'laptop-cho-sinh-vien-nen-chon-hang-nao-tot-nhat', '', '',
'2025-09-20 18:00:00', '2025-09-20 11:00:00', '', 0, '/?p=1010', 0, 'post', '', 18),

(1011, 1, '2025-10-01 20:00:00', '2025-10-01 13:00:00',
'<p><strong>Nắm vững nghiên cứu thị trường, xây dựng thương hiệu cá nhân, chăm sóc khách hàng và quản trị dòng tiền hiệu quả trong kỷ nguyên số.</strong></p><h2>1. Hiểu đúng nhu cầu khách hàng mục tiêu</h2><p>Phân tích hành vi mua sắm đa kênh và xây dựng phễu chuyển đổi tự nhiên.</p>',
'5 kỹ năng giúp bạn thành công trong kinh doanh online',
'Nắm vững nghiên cứu thị trường, xây dựng thương hiệu cá nhân, chăm sóc khách hàng và quản trị dòng tiền hiệu quả trong kỷ nguyên số.',
'publish', 'open', 'open', '5-ky-nang-giup-ban-thanh-cong-trong-kinh-doanh-online', '', '',
'2025-10-01 20:00:00', '2025-10-01 13:00:00', '', 0, '/?p=1011', 0, 'post', '', 25),

(1012, 1, '2025-09-30 19:30:00', '2025-09-30 12:30:00',
'<p><strong>Xây dựng chế độ dinh dưỡng cân bằng, vận động ngắn tại văn phòng và cải thiện chất lượng giấc ngủ chỉ với 20 phút mỗi ngày.</strong></p><h2>1. Quy tắc uống đủ nước và vận động sau mỗi 45 phút</h2><p>Giúp giảm căng thẳng cột sống, tăng cường tuần hoàn máu và duy trì sự tỉnh táo suốt ngày làm việc.</p>',
'Cách sống khỏe mỗi ngày dành cho người bận rộn',
'Xây dựng chế độ dinh dưỡng cân bằng, vận động ngắn tại văn phòng và cải thiện chất lượng giấc ngủ chỉ với 20 phút mỗi ngày.',
'publish', 'open', 'open', 'cach-song-khoe-moi-ngay-danh-cho-nguoi-ban-ron', '', '',
'2025-09-30 19:30:00', '2025-09-30 12:30:00', '', 0, '/?p=1012', 0, 'post', '', 16),

(1013, 1, '2025-09-27 14:00:00', '2025-09-27 07:00:00',
'<p><strong>Phương pháp phân bổ thu nhập khoa học giúp kiểm soát chi tiêu thiết yếu, tận hưởng cuộc sống và tích lũy quỹ dự phòng dài hạn.</strong></p><h2>1. Cách chia 3 nhóm ngân sách 50% - 30% - 20%</h2><p>50% cho nhu cầu thiết yếu, 30% cho mong muốn cá nhân và tối thiểu 20% cho tiết kiệm & đầu tư sinh lời.</p>',
'Quy tắc 50/30/20 trong quản lý tài chính cá nhân hiệu quả',
'Phương pháp phân bổ thu nhập khoa học giúp kiểm soát chi tiêu thiết yếu, tận hưởng cuộc sống và tích lũy quỹ dự phòng dài hạn.',
'publish', 'open', 'open', 'quy-tac-50-30-20-trong-quan-ly-tai-chinh-ca-nhan', '', '',
'2025-09-27 14:00:00', '2025-09-27 07:00:00', '', 0, '/?p=1013', 0, 'post', '', 9),

(1014, 1, '2025-09-26 09:00:00', '2025-09-26 02:00:00',
'<p><strong>Ứng dụng kỹ thuật Active Recall kết hợp Spaced Repetition để tăng gấp đôi hiệu suất tiếp thu kiến thức mới và ôn luyện chuyên sâu.</strong></p><h2>1. Active Recall là gì?</h2><p>Thay vì đọc lại thụ động, hãy chủ động đặt câu hỏi và tự truy xuất kiến thức từ trí nhớ sau mỗi chương sách.</p>',
'Phương pháp ghi nhớ nhanh và học tập chủ động Active Recall',
'Ứng dụng kỹ thuật Active Recall kết hợp Spaced Repetition để tăng gấp đôi hiệu suất tiếp thu kiến thức mới và ôn luyện chuyên sâu.',
'publish', 'open', 'open', 'phuong-phap-ghi-nho-nhanh-va-hoc-tap-chu-dong-active-recall', '', '',
'2025-09-26 09:00:00', '2025-09-26 02:00:00', '', 0, '/?p=1014', 0, 'post', '', 11)
ON DUPLICATE KEY UPDATE
	`post_title` = VALUES(`post_title`),
	`post_excerpt` = VALUES(`post_excerpt`),
	`post_content` = VALUES(`post_content`);

-- 3. GÁN BÀI VIẾT VÀO CHUYÊN MỤC (wpig_term_relationships)
INSERT IGNORE INTO `wpig_term_relationships` (`object_id`, `term_taxonomy_id`, `term_order`) VALUES
(1001, 101, 0),
(1002, 101, 0),
(1003, 101, 0),
(1004, 101, 0),
(1005, 101, 0),
(1006, 101, 0),
(1007, 102, 0),
(1008, 102, 0),
(1009, 102, 0),
(1010, 102, 0),
(1011, 103, 0),
(1012, 105, 0),
(1013, 104, 0),
(1014, 106, 0);

-- 4. LƯỢT XEM BÀI VIẾT (wpig_postmeta: post_views)
INSERT INTO `wpig_postmeta` (`post_id`, `meta_key`, `meta_value`) VALUES
(1001, 'post_views', '12400'),
(1002, 'post_views', '15200'),
(1003, 'post_views', '6800'),
(1004, 'post_views', '7400'),
(1005, 'post_views', '8200'),
(1006, 'post_views', '9800'),
(1007, 'post_views', '8700'),
(1008, 'post_views', '12800'),
(1009, 'post_views', '9300'),
(1010, 'post_views', '7900'),
(1011, 'post_views', '8500'),
(1012, 'post_views', '8900'),
(1013, 'post_views', '6400'),
(1014, 'post_views', '6100');
