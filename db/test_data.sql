USE ip_project;

INSERT INTO user_account
VALUES
(2001, 'Supplier 1', '011105020803', 2),
(2002, 'Supplier 2', '020809030641', 2),
(2003, 'Supplier 3', '010131020802', 2),
(3001, 'Agent 3', '010131020802', 3),
(3002, 'Agent 3', '970223090378', 3),
(3003, 'Agent 3', '991203029023', 3);

INSERT INTO role_supplier
VALUES
(2001),
(2002),
(2003);

INSERT INTO role_agent
VALUES
(3001, 2001),
(3002, 2002),
(3003, 2002);

INSERT INTO user_login_data
VALUES
(2001, '2001', '1234'),
(2002, '2002', '1234'),
(2003, '2003', '1234'),
(3001, '3001', '1234'),
(3002, '3002', '1234'),
(3003, '3003', '1234');

INSERT INTO item
VALUES
(1001, 'Item A', '', 1.5, 0, NOW(), NOW(), 2001),
(1002, 'Item B', '', 2.5, 0, NOW(), NOW(), 2001),
(1003, 'Item C', '', 1.5, 0, NOW(), NOW(), 2001),
(1004, 'Item D', '', 3.5, 0, NOW(), NOW(), 2001),
(1005, 'Item E', '', 1.5, 0, NOW(), NOW(), 2002),
(1006, 'Item F', '', 4.0, 0, NOW(), NOW(), 2002),
(1007, 'Item G', '', 1.5, 0, NOW(), NOW(), 2003);

INSERT INTO approval
VALUES
(1001, 2, 2001, NOW(), NOW()),
(1002, 1, 2001, NOW(), NOW()),
(1003, 3, 2002, NOW(), NOW()),
(1004, 1, 2002, NOW(), NOW()),
(1005, 1, 2002, NOW(), NOW());

INSERT INTO sales_order
VALUES
(1001, 'Customer A', '', '012345678910', NOW(), 3001, 1001),
(1002, 'Customer B', '', '012345678910', NOW(), 3001, 1002),
(1003, 'Customer C', '', '012345678910', NOW(), 3002, 1003),
(1004, 'Customer D', '', '012345678910', NOW(), 3002, 1004),
(1005, 'Customer E', '', '012345678910', NOW(), 3002, 1005);

INSERT INTO sales_order_line
VALUES
(1001, 1001, 2),
(1001, 1002, 1),
(1002, 1001, 5),
(1002, 1003, 6),
(1003, 1006, 100),
(1004, 1005, 10),
(1005, 1005, 5);