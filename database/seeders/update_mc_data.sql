-- Clear existing data
DELETE FROM master_cards;

-- Insert master card data
INSERT INTO master_cards (mc_seq, mc_model, part_no, comp_no, p_design, status, ext_dim_1, ext_dim_2, ext_dim_3, int_dim_1, int_dim_2, int_dim_3, created_at, updated_at) VALUES
('1609138', 'BOX BASO 4,5 KG', 'BOX', 'Main', 'B1', 'Act', '396', '243', '297', '393', '240', '292', NOW(), NOW()),
('1609144', 'BOX IKAN HARIMAU 4.5 KG', 'BOX', 'Main', 'B1', 'Act', '396', '243', '297', '393', '240', '292', NOW(), NOW()),
('1609145', 'BOX SRIKAYA 4.5 KG', 'BOX', 'Main', 'B1', 'Act', '396', '243', '297', '393', '240', '292', NOW(), NOW()),
('1609162', 'BIHUN FANIA 5 KG', 'BOX', 'Main', 'B1', 'Act', '396', '243', '297', '393', '240', '292', NOW(), NOW()),
('1609163', 'BIHUN IKAN TUNA 4.5 KG BARU', 'BOX', 'Main', 'B1', 'Act', '396', '243', '297', '393', '240', '292', NOW(), NOW()),
('1609164', 'BIHUN IKAN TUNA 5 KG BARU', 'BOX', 'Main', 'B1', 'Act', '396', '243', '297', '393', '240', '292', NOW(), NOW()),
('1609166', 'BIHUN PIRING MAS 5 KG', 'BOX', 'Main', 'B1', 'Act', '396', '243', '297', '393', '240', '292', NOW(), NOW()),
('1609173', 'BOX JAGUNG SRIKAYA 5 KG', 'BOX', 'Main', 'B1', 'Act', '396', '243', '297', '393', '240', '292', NOW(), NOW()),
('1609181', 'BIHUN POHON KOPI 5 KG', 'BOX', 'Main', 'B1', 'Act', '396', '243', '297', '393', '240', '292', NOW(), NOW()),
('1609182', 'BIHUN DELLIS 5 KG', 'BOX', 'Main', 'B1', 'Act', '396', '243', '297', '393', '240', '292', NOW(), NOW()),
('1609185', 'POLOS UK 506 X 356 X 407', 'BOX', 'Main', 'B1', 'Act', '506', '356', '407', '503', '353', '402', NOW(), NOW()),
('1609186', 'POLOS 480 X 410 X 401', 'BOX', 'Main', 'B1', 'Act', '480', '410', '401', '477', '407', '396', NOW(), NOW()); 