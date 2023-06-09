USE ip_project;

INSERT INTO user_account (UserId, UserName, ICNumber, RoleId)
VALUES 
(2001, 'Muhammad Nazri bin Murad', '011105020803', 2),
(2002, 'Shahrul bin Nizam', '020809030641', 2),
(2003, 'Shahrina binti Shahrizan', '010131020802', 2),
(3001, 'Uwais bin Abdullah', '010131020801', 3),
(3002, 'Roziah binti Puteh', '970223090378', 3),
(3003, 'Arif bid Mat Arif', '991203029023', 3);

INSERT INTO role_supplier (SupplierId)
VALUES
(2001),
(2002),
(2003);

INSERT INTO role_agent (AgentId, SupplierId)
VALUES
(3001, 2001),
(3002, 2002),
(3003, 2002);

INSERT INTO user_login_data (UserId, LoginName, LoginPassword)
VALUES
(2001, '2001', '1234'),
(2002, '2002', '1234'),
(2003, '2003', '1234'),
(3001, '3001', '1234'),
(3002, '3002', '1234'),
(3003, '3003', '1234');

INSERT INTO item (ItemId, ItemName, ItemDescription, ItemPrice, ItemQuantity, SupplierId)
VALUES
(1001, 'Pencil', '2b Stabilo', 1.5, 100, 2001),
(1002, 'Pen', 'Pilot', 2.5, 50, 2001),
(1003, 'Rubber', 'Stabilo', 1.5, 90, 2001),
(1004, 'Highlighter', 'Skyler', 3.5, 20, 2001),
(1005, 'Ruler', '15 Centimeter', 1.5, 10, 2002),
(1006, 'Colour Pencil', 'Swan', 4.0, 999, 2002),
(1007, 'Sharpener', 'Stabilo', 1.5, 0, 2003);

INSERT INTO approval (ApprovalId, ApprovalStatusId, ApprovedBy)
VALUES
(1001, 2, 2001),
(1002, 2, 2001),
(1003, 3, 2002),
(1004, 1, 2002),
(1005, 2, 2002);

INSERT INTO sales_order (SalesOrderId, CustomerName, CustomerAddress, ContactNumber, CreatedBy, ApprovalId)
VALUES
(1001, 'Nasaruddin bin ahmad', '1st Floor, Jln Kuchai Maju 8, 58200 Kuala Lumpur', '012345678910', 3001, 1001),
(1002, 'Nazrul azni bin kalisa asri', '501 Block C5 Seksyen 10 Wangsa Maju 53300 Wilayah Persekutuan Malaysia', '012345678910', 3001, 1002),
(1003, 'Alif ezekiyel bin md ruf', 'Lot 4 Jalan Perak Telok Panglima Garang Industrial Estate ', '012345678910', 3002, 1003),
(1004, 'Aina intan binti kamusari', 'Jalan Zaaba 70100 Seremban', '012345678910', 3002, 1004),
(1005, 'Nur insan mardiana binti halim', '524 Lrg Dua Belas Taman Acbe 72100 Bahau', '012345678910', 3002, 1005);

INSERT INTO sales_order_line (SalesOrderId, ItemId, Quantity)
VALUES
(1001, 1001, 2),
(1001, 1002, 1),
(1002, 1001, 5),
(1002, 1003, 6),
(1003, 1006, 100),
(1004, 1005, 10),
(1005, 1005, 5);
