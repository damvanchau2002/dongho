-- Xóa dữ liệu cũ (Tùy chọn - Bỏ comment nếu muốn xóa trắng dữ liệu cũ trước khi thêm)
-- SET FOREIGN_KEY_CHECKS = 0;
-- TRUNCATE TABLE sanpham;
-- TRUNCATE TABLE loaisanpham;
-- SET FOREIGN_KEY_CHECKS = 1;

-- Sửa lại kiểu dữ liệu của giá sản phẩm để chứa được các mức giá hàng tỷ đồng
ALTER TABLE `sanpham` MODIFY COLUMN `gia_sp` DECIMAL(20,2) DEFAULT 0;
ALTER TABLE `sanpham` MODIFY COLUMN `flash_sale_price` DECIMAL(20,2) DEFAULT 0;

-- =======================================================
-- 1. THÊM DỮ LIỆU THƯƠNG HIỆU (LOẠI SẢN PHẨM)
-- =======================================================
INSERT INTO `loaisanpham` (`id_loaisp`, `ten_loaisp`) VALUES
(1, 'Rolex'),
(2, 'Patek Philippe'),
(3, 'Audemars Piguet'),
(4, 'Omega'),
(5, 'Hublot'),
(6, 'Vacheron Constantin'),
(7, 'Casio'),
(8, 'Tissot'),
(9, 'KOI')
ON DUPLICATE KEY UPDATE `ten_loaisp` = VALUES(`ten_loaisp`);

-- =======================================================
-- 2. THÊM DỮ LIỆU SẢN PHẨM (45 SẢN PHẨM)
-- =======================================================
-- Sử dụng ảnh mẫu từ Unsplash chất lượng cao để giao diện hiển thị đẹp nhất
-- Hình ảnh mang tính chất minh họa cho đồng hồ cao cấp

INSERT INTO `sanpham` (`ten_sp`, `hinhanh_sp`, `gia_sp`, `id_loaisp`, `ngaynhap_sp`, `mota_sp`, `flash_sale_price`, `flash_sale_end`) VALUES

-- --- ROLEX (ID: 1) ---
('Rolex Submariner Date', 'https://images.unsplash.com/photo-1523170335258-f5ed11844a49?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80', 350000000, 1, '2024-01-10', 'Rolex Submariner Date với mặt số màu đen và vòng bezel gốm Cerachrom. Một biểu tượng của những chiếc đồng hồ thợ lặn, Submariner Date mang đậm dấu ấn thiết kế không thể nhầm lẫn.', 0, NULL),
('Rolex Daytona Cosmograph', 'https://images.unsplash.com/photo-1548171915-e7afacaab04a?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80', 520000000, 1, '2024-02-15', 'Oyster Perpetual Cosmograph Daytona bằng thép Oystersteel với mặt số màu trắng và dây đeo Oyster. Chiếc đồng hồ dành riêng cho những tay đua chuyên nghiệp.', 0, NULL),
('Rolex Datejust 36', 'https://images.unsplash.com/photo-1587836374828-cb4387df3eb7?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80', 210000000, 1, '2024-03-01', 'Đồng hồ Rolex Datejust 36 cổ điển với mặt số màu xanh champagne sang trọng. Dây đeo Jubilee biểu tượng cùng bộ máy cơ học tự động siêu chính xác.', 199000000, DATE_ADD(NOW(), INTERVAL 2 DAY)),
('Rolex GMT-Master II (Pepsi)', 'https://images.unsplash.com/photo-1614164185128-e4ec99c436d7?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80', 480000000, 1, '2024-03-10', 'Phiên bản GMT-Master II vành bezel hai màu đỏ-xanh dương huyền thoại. Cho phép theo dõi đồng thời múi giờ ở hai địa điểm khác nhau.', 0, NULL),
('Rolex Oyster Perpetual 41', 'https://images.unsplash.com/photo-1524592094714-0f0654e20314?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80', 160000000, 1, '2024-03-20', 'Thiết kế tinh gọn, thuần túy nhưng mang lại nét thanh lịch tối đa. Mặt số Tiffany Blue nổi bật và độc đáo.', 0, NULL),

-- --- PATEK PHILIPPE (ID: 2) ---
('Patek Philippe Nautilus 5711', 'https://images.unsplash.com/photo-1612817159949-195b6eb9e31a?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80', 2500000000, 2, '2024-01-05', 'Biểu tượng của sự xa xỉ thể thao. Nautilus 5711 mặt xanh huyền thoại là giấc mơ của mọi nhà sưu tầm đồng hồ trên thế giới.', 0, NULL),
('Patek Philippe Aquanaut 5167A', 'https://images.unsplash.com/photo-1509048191080-d2984bad6ae5?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80', 1200000000, 2, '2024-02-10', 'Trẻ trung, hiện đại và thể thao. Dây đeo composite siêu bền kết hợp cùng mặt số dập nổi đặc trưng của dòng Aquanaut.', 0, NULL),
('Patek Philippe Calatrava 5227', 'https://images.unsplash.com/photo-1522312346375-d1a52e2b99b3?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80', 850000000, 2, '2024-03-15', 'Tinh hoa của đồng hồ Dress Watch. Thiết kế tối giản, thanh lịch vượt thời gian bằng vàng hồng 18K.', 0, NULL),
('Patek Philippe Grand Complications', 'https://images.unsplash.com/photo-1533139502658-0198f920d8e8?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80', 3800000000, 2, '2024-04-01', 'Kiệt tác cơ khí phức tạp với chức năng Lịch vạn niên (Perpetual Calendar) và điểm chuông (Minute Repeater).', 0, NULL),
('Patek Philippe Twenty~4', 'https://images.unsplash.com/photo-1549972574-87ccdbf5e227?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80', 650000000, 2, '2024-04-10', 'Dòng đồng hồ thanh lịch dành riêng cho phái đẹp. Vỏ chữ nhật đính kim cương tinh xảo trên viền bezel.', 0, NULL),

-- --- AUDEMARS PIGUET (ID: 3) ---
('AP Royal Oak Selfwinding', 'https://images.unsplash.com/photo-1622434641406-a158123450f9?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80', 1100000000, 3, '2024-01-20', 'Thiết kế bát giác đặc trưng với 8 ốc vít lục giác lộ ra ngoài. Mặt số họa tiết Grande Tapisserie cực kỳ cuốn hút.', 1050000000, DATE_ADD(NOW(), INTERVAL 3 DAY)),
('AP Royal Oak Chronograph', 'https://images.unsplash.com/photo-1594534475808-b18fc33b045e?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80', 1400000000, 3, '2024-02-25', 'Phiên bản bấm giờ thể thao của dòng Royal Oak. Mặt số Panda tương phản mạnh mẽ mang lại sự khỏe khoắn.', 0, NULL),
('AP Royal Oak Offshore', 'https://images.unsplash.com/photo-1614729939124-032f0b56c9ce?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80', 950000000, 3, '2024-03-05', 'Bản nâng cấp hầm hố hơn của Royal Oak. Vật liệu Ceramic cao cấp kết hợp dây cao su thể thao siêu bền bỉ.', 0, NULL),
('AP Code 11.59', 'https://images.unsplash.com/photo-1585123334904-845d60e97b29?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80', 750000000, 3, '2024-04-12', 'Sự pha trộn giữa vỏ tròn và lồng bát giác bên trong. Kính sapphire cong kép tạo hiệu ứng thị giác độc đáo.', 0, NULL),
('AP Royal Oak Concept', 'https://images.unsplash.com/photo-1508685096489-7aacd43bd3b1?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80', 4500000000, 3, '2024-04-20', 'Thiết kế mang tính tương lai với bộ máy Tourbillon bay siêu phức tạp phô diễn toàn bộ ra mặt trước.', 0, NULL),

-- --- OMEGA (ID: 4) ---
('Omega Speedmaster Moonwatch', 'https://images.unsplash.com/photo-1517505963283-74b0cc0d79d6?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80', 180000000, 4, '2024-01-15', 'Chiếc đồng hồ đầu tiên đi lên mặt trăng. Sử dụng bộ máy cơ lên cót tay Caliber 3861 chống từ trường chuẩn Master Chronometer.', 0, NULL),
('Omega Seamaster Diver 300M', 'https://images.unsplash.com/photo-1594534475808-b18fc33b045e?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80', 145000000, 4, '2024-02-05', 'Sự lựa chọn của điệp viên 007. Mặt số gốm khắc họa tiết vân sóng laser đặc trưng cùng van thoát khí Heli chuyên dụng.', 125000000, DATE_ADD(NOW(), INTERVAL 1 DAY)),
('Omega Seamaster Aqua Terra', 'https://images.unsplash.com/photo-1501162946741-4960f91ce424?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80', 155000000, 4, '2024-02-28', 'Sự giao thoa hoàn hảo giữa thể thao và thanh lịch. Mặt số họa tiết gỗ teak lấy cảm hứng từ boong tàu du thuyền sang trọng.', 0, NULL),
('Omega Constellation Globemaster', 'https://images.unsplash.com/photo-1623998021446-45cd9b269056?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80', 195000000, 4, '2024-03-22', 'Mặt số bát úp (Pie-pan dial) cổ điển kết hợp niềng khía rãnh. Đạt chuẩn chứng nhận siêu sai số METAS khắt khe nhất thế giới.', 0, NULL),
('Omega De Ville Prestige', 'https://images.unsplash.com/photo-1595180435422-540bb92b4cb1?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80', 95000000, 4, '2024-04-18', 'Đồng hồ Dress watch tối giản và mỏng nhẹ, vỏ bằng vàng Sedna nguyên khối độc quyền của Omega.', 0, NULL),

-- --- HUBLOT (ID: 5) ---
('Hublot Big Bang Unico', 'https://images.unsplash.com/photo-1508685096489-7aacd43bd3b1?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80', 520000000, 5, '2024-01-25', 'Nghệ thuật hợp nhất (Art of Fusion) với bộ vỏ Ceramic đen nhám. Bộ máy Unico lộ cơ hoàn toàn khoe vẻ đẹp cơ khí ở mặt số.', 480000000, DATE_ADD(NOW(), INTERVAL 5 DAY)),
('Hublot Classic Fusion Titanium', 'https://images.unsplash.com/photo-1549972574-87ccdbf5e227?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80', 185000000, 5, '2024-02-12', 'Phiên bản thanh lịch và dễ đeo nhất của Hublot. Vỏ Titanium siêu nhẹ, mặt số màu xanh Blue Sunray cuốn hút.', 0, NULL),
('Hublot Spirit of Big Bang', 'https://images.unsplash.com/photo-1524592094714-0f0654e20314?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80', 650000000, 5, '2024-03-08', 'Dáng vỏ Tonneau (chum rượu) độc đáo mang DNA của Big Bang. Kết hợp vật liệu vàng King Gold và cao su vân cá sấu.', 0, NULL),
('Hublot Sang Bleu II', 'https://images.unsplash.com/photo-1622434641406-a158123450f9?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80', 850000000, 5, '2024-03-30', 'Tuyệt tác hình học hợp tác cùng nghệ sĩ xăm hình Maxime Plescia-Büchi. Hiển thị thời gian qua các đĩa quay đa giác.', 0, NULL),
('Hublot Big Bang MP-11', 'https://images.unsplash.com/photo-1612817288484-6f916006741a?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80', 1900000000, 5, '2024-04-22', 'Trữ cót khủng lên tới 14 ngày với 7 hộp cót xếp thẳng hàng lộ rõ trên mặt số. Vỏ sapphire trong suốt nguyên khối.', 0, NULL),

-- --- VACHERON CONSTANTIN (ID: 6) ---
('VC Overseas 4500V', 'https://images.unsplash.com/photo-1548171915-e7afacaab04a?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80', 750000000, 6, '2024-01-30', 'Chiếc đồng hồ thể thao thuộc hàng Holy Trinity. Mặt số xanh ngọc bích sâu thẳm, đi kèm 3 bộ dây có thể tự thay nhanh chóng.', 0, NULL),
('VC Patrimony Manual-Winding', 'https://images.unsplash.com/photo-1522312346375-d1a52e2b99b3?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80', 550000000, 6, '2024-02-18', 'Thiết kế tròn hoàn hảo, siêu mỏng. Kế thừa di sản thiết kế tối giản từ những năm 1950 của Vacheron Constantin.', 0, NULL),
('VC Traditionnelle Tourbillon', 'https://images.unsplash.com/photo-1533139502658-0198f920d8e8?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80', 3200000000, 6, '2024-03-12', 'Lồng Tourbillon có tạo hình chữ thập Maltese mang tính biểu tượng, hoàn thiện thủ công với tiêu chuẩn Geneva Seal cao cấp nhất.', 0, NULL),
('VC Fiftysix Day-Date', 'https://images.unsplash.com/photo-1612817159949-195b6eb9e31a?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80', 480000000, 6, '2024-04-02', 'Lấy cảm hứng từ nguyên mẫu năm 1956, mang nét đẹp hoài cổ giao thoa hiện đại với chỉ báo Ngày và Thứ tiện dụng.', 0, NULL),
('VC Historiques American 1921', 'https://images.unsplash.com/photo-1587836374828-cb4387df3eb7?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80', 920000000, 6, '2024-04-25', 'Thiết kế mặt số nghiêng 45 độ vô cùng phá cách, dành cho những tài xế lái xe thời xưa có thể xem giờ mà không cần rời tay khỏi vô lăng.', 0, NULL),

-- --- CASIO (ID: 7) ---
('Casio G-Shock Mudmaster', 'https://images.unsplash.com/photo-1618331835717-801e976710b2?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80', 12500000, 7, '2024-01-08', 'G-Shock cấu trúc chống bùn lầy, chống shock cực đoan. Tích hợp la bàn số, đo độ cao, đo áp suất và nhiệt độ.', 0, NULL),
('Casio Edifice Bluetooth', 'https://images.unsplash.com/photo-1585123334904-845d60e97b29?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80', 6800000, 7, '2024-02-02', 'Đồng hồ thể thao đua xe thông minh, kết nối Bluetooth với smartphone để tự động cập nhật giờ thế giới chính xác.', 5500000, DATE_ADD(NOW(), INTERVAL 4 DAY)),
('Casio Vintage Illuminator', 'https://images.unsplash.com/photo-1549972574-87ccdbf5e227?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80', 1500000, 7, '2024-02-22', 'Chiếc đồng hồ điện tử mạ vàng huyền thoại. Thiết kế cổ điển retro từ thập niên 80 vẫn cực kỳ được yêu thích.', 990000, DATE_ADD(NOW(), INTERVAL 2 DAY)),
('Casio G-Shock Full Metal', 'https://images.unsplash.com/photo-1614164185128-e4ec99c436d7?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80', 16500000, 7, '2024-03-18', 'Sự lột xác của G-Shock với bộ vỏ thép không gỉ nguyên khối. Tích hợp năng lượng mặt trời Tough Solar.', 0, NULL),
('Casio Pro Trek Titanium', 'https://images.unsplash.com/photo-1614729939124-032f0b56c9ce?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80', 14200000, 7, '2024-04-08', 'Dòng đồng hồ dành cho leo núi chuyên nghiệp. Vỏ titanium siêu nhẹ chống dị ứng, trang bị bộ 3 cảm biến Triple Sensor.', 0, NULL),

-- --- TISSOT (ID: 8) ---
('Tissot PRX Powermatic 80', 'https://images.unsplash.com/photo-1501162946741-4960f91ce424?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80', 19500000, 8, '2024-01-12', 'Tuyệt phẩm dáng dây đeo tích hợp (integrated bracelet) hoài cổ thập niên 70. Bộ máy cơ trữ cót 80 giờ siêu việt.', 0, NULL),
('Tissot Le Locle Automatic', 'https://images.unsplash.com/photo-1517505963283-74b0cc0d79d6?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80', 16800000, 8, '2024-02-08', 'Tên gọi tri ân quê hương của Tissot. Mặt số vân họa tiết Clous de Paris đậm chất truyền thống Thụy Sĩ thanh lịch.', 14500000, DATE_ADD(NOW(), INTERVAL 1 DAY)),
('Tissot Seastar 1000', 'https://images.unsplash.com/photo-1623998021446-45cd9b269056?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80', 22500000, 8, '2024-03-02', 'Đồng hồ lặn mạnh mẽ, kháng nước tới áp suất 30 bar. Vòng bezel bằng gốm ceramic chống xước tuyệt đối.', 0, NULL),
('Tissot Gentleman Titanium', 'https://images.unsplash.com/photo-1595180435422-540bb92b4cb1?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80', 24000000, 8, '2024-03-25', 'Đồng hồ hoàn hảo cho quý ông công sở hiện đại. Dây thép đánh bóng xen kẽ chải xước, chống nhiễm từ trường nhờ dây tóc Silicon.', 0, NULL),
('Tissot Chemin Des Tourelles', 'https://images.unsplash.com/photo-1509048191080-d2984bad6ae5?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80', 21500000, 8, '2024-04-15', 'Sự kiêu hãnh của lịch sử. Mặt số phay xước tia mặt trời lấp lánh kết hợp bộ kim Alpha vót nhọn tinh tế sắc sảo.', 0, NULL),

-- --- KOI (ID: 9) ---
('KOI Classic Gold Minimalist', 'https://images.unsplash.com/photo-1522312346375-d1a52e2b99b3?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80', 4500000, 9, '2024-01-05', 'Đồng hồ thương hiệu KOI thiết kế tối giản, vỏ mạ vàng PVD công nghệ cao không phai màu. Phù hợp cho dân văn phòng.', 3200000, DATE_ADD(NOW(), INTERVAL 2 DAY)),
('KOI Sapphire Blue Dial', 'https://images.unsplash.com/photo-1612817288484-6f916006741a?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80', 5200000, 9, '2024-02-14', 'Mặt số xanh navy lấp lánh bắt sáng. Trang bị kính Sapphire nguyên khối chống trầy tuyệt đối và máy Quartz bền bỉ.', 0, NULL),
('KOI Lady Mesh Rose Gold', 'https://images.unsplash.com/photo-1549972574-87ccdbf5e227?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80', 4800000, 9, '2024-03-08', 'Phiên bản dành cho phái đẹp với dây lưới lụa (Milanese) mềm mại ôm trọn cổ tay. Màu vàng hồng tôn da cực tốt.', 0, NULL),
('KOI Chronograph Sport', 'https://images.unsplash.com/photo-1618331835717-801e976710b2?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80', 6500000, 9, '2024-03-28', 'Dòng đồng hồ bấm giờ thể thao mạnh mẽ của KOI. Mặt số 6 kim với 3 sub-dial hiển thị giây và phút bấm giờ chi tiết.', 0, NULL),
('KOI Automatic Open Heart', 'https://images.unsplash.com/photo-1587836374828-cb4387df3eb7?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80', 8900000, 9, '2024-04-18', 'Đồng hồ máy cơ tự động (Automatic) của KOI với cửa sổ lộ tim góc 9 giờ, phô diễn nhịp đập sống động của bộ máy.', 7500000, DATE_ADD(NOW(), INTERVAL 5 DAY));
