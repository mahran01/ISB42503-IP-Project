DROP DATABASE IF EXISTS ip_project;

CREATE DATABASE IF NOT EXISTS ip_project;

USE ip_project;

CREATE TABLE IF NOT EXISTS user_role (
    RoleId INT NOT NULL PRIMARY KEY,
    RoleName VARCHAR(10) NOT NULL
);

INSERT INTO user_role
VALUES 
(1, 'Admin'),
(2, 'Supplier'),
(3, 'Agent');

CREATE TABLE IF NOT EXISTS user_account (
    UserId INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    UserName VARCHAR(100) NOT NULL,
    ICNumber VARCHAR(12) NOT NULL,
    RoleId INT NOT NULL,
    FOREIGN KEY (RoleId) REFERENCES user_role(RoleId)
);

INSERT INTO user_account
VALUES (1, 'Admin', '000000000000', 1);

CREATE TABLE IF NOT EXISTS user_login_data (
    UserId INT NOT NULL,
    LoginName VARCHAR(50) UNIQUE NOT NULL,
    LoginPassword VARCHAR(25) NOT NULL,
    FOREIGN KEY (UserId) REFERENCES user_account(UserId)
);

INSERT INTO user_login_data
VALUES (1, 'admin', '1234');

CREATE TABLE IF NOT EXISTS role_supplier (
    SupplierId INT NOT NULL,
    FOREIGN KEY (SupplierId) REFERENCES user_account(UserId)
);

CREATE TABLE IF NOT EXISTS role_agent (
    AgentId INT NOT NULL,
    SupplierId  INT NOT NULL,
    FOREIGN KEY (AgentId) REFERENCES user_account(UserId),
    FOREIGN KEY (SupplierId) REFERENCES role_supplier(SupplierId)
);

CREATE TABLE IF NOT EXISTS approval_status (
    ApprovalStatusId INT NOT NULL PRIMARY KEY,
    ApprovalStatusName VARCHAR(10) NOT NULL
);

INSERT INTO approval_status
VALUES 
(1, 'Pending'),
(2, 'Approved'),
(3, 'Declined');

CREATE TABLE IF NOT EXISTS approval (
    ApprovalId INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    ApprovalStatusId INT NOT NULL DEFAULT 1,
    ApprovedBy INT,
    DateCreated DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    DateUpdated DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (ApprovalStatusId) REFERENCES approval_status(ApprovalStatusId),
    FOREIGN KEY (ApprovedBy) REFERENCES role_supplier(SupplierId)
);

CREATE TABLE IF NOT EXISTS sales_order (
    SalesOrderId INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    CustomerName VARCHAR(100) NOT NULL,
    CustomerAddress VARCHAR(250) NOT NULL,
    ContactNumber VARCHAR(15) NOT NULL,
    DateCreated DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CreatedBy INT NOT NULL,
    ApprovalId INT NOT NULL,
    FOREIGN KEY (CreatedBy) REFERENCES role_agent(AgentId),
    FOREIGN KEY (ApprovalId) REFERENCES approval(approvalId)
);

CREATE TABLE IF NOT EXISTS item (
    ItemId INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    ItemName VARCHAR(100) NOT NULL,
    ItemDescription VARCHAR(255) NOT NULL,
    ItemPrice DECIMAL(10,2) NOT NULL,
    ItemQuantity INT NOT NULL,
    DateCreated DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    DateUpdated DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    SupplierId INT NOT NULL,
    FOREIGN KEY (SupplierId) REFERENCES role_supplier(SupplierId)
);

CREATE TABLE IF NOT EXISTS sales_order_line (
    SalesOrderId INT NOT NULL,
    ItemId INT NOT NULL,
    Quantity INT NOT NULL,
    FOREIGN KEY (SalesOrderId) REFERENCES sales_order(SalesOrderId),
    FOREIGN KEY (ItemId) REFERENCES item(ItemId)
);
