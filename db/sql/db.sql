-- creates the database tables

CREATE TABLE `costumers` ( 
    `id` INT NOT NULL AUTO_INCREMENT COMMENT 'Stores the unique user ID' , 
    `name` VARCHAR(100) NOT NULL COMMENT 'Stores the user name' , 
    `pwd` VARCHAR(64) NOT NULL COMMENT 'Stores the user password, encrypted by hash' , 
    `email` VARCHAR(100) NOT NULL COMMENT 'Stores the user e-mail used to login' , 
    `phone` VARCHAR(12) NOT NULL COMMENT 'Stores the user phone number' , 
    `admin` BOOLEAN NOT NULL COMMENT 'Indicates if the user is administrator (or not)' ,
    PRIMARY KEY (`id`)
) ENGINE = InnoDB COMMENT = 'Stores information about the costumer';

CREATE TABLE `services` ( 
    `name` VARCHAR(50) NOT NULL COMMENT 'Stores the service name' , 
    `type` VARCHAR(50) NOT NULL COMMENT 'Stores the service type (hair, nails, hair removal or face' , 
    `price` FLOAT NOT NULL COMMENT 'Stores the service price' , 
    `time` FLOAT NOT NULL COMMENT 'Stores the required amount of time to conclude the service, in hours. E.g.: 1.5 (hours)' , 
    PRIMARY KEY (`name`)
) ENGINE = InnoDB COMMENT = 'Stores information about the available services';

CREATE TABLE `appointments` ( 
    `id` INT NOT NULL AUTO_INCREMENT COMMENT 'ID of the appointment' , 
    `costumer` INT NOT NULL COMMENT 'ID of the costume who made the appointment' , 
    `date` DATE NOT NULL COMMENT 'Date of the appointment' , 
    `time` TIME NOT NULL COMMENT 'Time of the appointment' ,
    `status` VARCHAR(10) NOT NULL COMMENT 'Status of the appointment (in service, booked, completed, unfinished)' , 
    `additional_info` TEXT NOT NULL COMMENT 'Additional information added by the costumer' , 
    `price` FLOAT NOT NULL COMMENT 'Total price of the order' ,
    `staff` INT NOT NULL COMMENT 'Number of the staff member/team responsible by the appointment' , 
    PRIMARY KEY (`id`)
) ENGINE = InnoDB COMMENT = 'Stores the appointments';

CREATE TABLE `appointment_services` ( 
    `id` INT NOT NULL AUTO_INCREMENT COMMENT 'ID of the appointment with this service' ,
    `appointment` INT NOT NULL COMMENT 'ID of the appointment' , 
    `service` VARCHAR(50) NOT NULL COMMENT 'Name of the service in the appointment' ,
    PRIMARY KEY (`id`) , 
    INDEX `appointment` (`appointment`, `service`)
) ENGINE = InnoDB COMMENT = 'Stores the services ordered in specified appointment';

CREATE TABLE `appointment_charges` ( 
    `id` INT NOT NULL AUTO_INCREMENT COMMENT 'ID of the charge' ,
    `appointment` INT NOT NULL COMMENT 'ID of the appointment' , 
    `charge` FLOAT NOT NULL COMMENT 'The price of the charge' , 
    `reason` TEXT NOT NULL COMMENT 'The reason for the additional charge' ,
    PRIMARY KEY (`id`)
) ENGINE = InnoDB COMMENT = 'Stores the additional charges in determined appointment';

ALTER TABLE `appointments` ADD CONSTRAINT `appointment-costumer` FOREIGN KEY (`costumer`) REFERENCES `costumers`(`id`) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE `appointment_services` ADD CONSTRAINT `appointment-as` FOREIGN KEY (`appointment`) REFERENCES `appointments`(`id`) ON DELETE CASCADE ON UPDATE CASCADE; 
ALTER TABLE `appointment_services` ADD CONSTRAINT `service-as` FOREIGN KEY (`service`) REFERENCES `services`(`name`) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE `appointment_charges` ADD CONSTRAINT `appointment-charge` FOREIGN KEY (`appointment`) REFERENCES `appointments`(`id`) ON DELETE CASCADE ON UPDATE CASCADE;

-- adds the administrator account

INSERT INTO `costumers` (`name`, `pwd`, `email`, `phone`, `admin`) VALUES ('Administrator', '$2y$10$6rM0FslnpNkTYkKFiLBl9.wULihHBjaXgEAlJQjJL4WFJ8Cs1G9qa', 'admin@admin.com', '123-456-789', 1);

-- adds the initial services
INSERT INTO `services` (`name`, `type`, `price`, `time`) VALUES ('Haircut', 'Hair', 30, 1);
INSERT INTO `services` (`name`, `type`, `price`, `time`) VALUES ('Colouring', 'Hair', 40, 2);
INSERT INTO `services` (`name`, `type`, `price`, `time`) VALUES ('Blow-drys', 'Hair', 15, 1);
INSERT INTO `services` (`name`, `type`, `price`, `time`) VALUES ('Highlights', 'Hair', 40, 3);
INSERT INTO `services` (`name`, `type`, `price`, `time`) VALUES ('Treatments', 'Hair', 20, 1);
INSERT INTO `services` (`name`, `type`, `price`, `time`) VALUES ('File and Polish - Hands', 'Nails', 15, 1);
INSERT INTO `services` (`name`, `type`, `price`, `time`) VALUES ('Shellac', 'Nails', 25, 2);
INSERT INTO `services` (`name`, `type`, `price`, `time`) VALUES ('File and Polish - Toes', 'Nails', 18, 1);
INSERT INTO `services` (`name`, `type`, `price`, `time`) VALUES ('Shellac - Toes', 'Nails', 22, 2);
INSERT INTO `services` (`name`, `type`, `price`, `time`) VALUES ('Face Makeup', 'Face', 30, 1);
INSERT INTO `services` (`name`, `type`, `price`, `time`) VALUES ('Face Makeup + Eyebrown', 'Face', 40, 2);
INSERT INTO `services` (`name`, `type`, `price`, `time`) VALUES ('Eyebrown', 'Face', 13, 1);
INSERT INTO `services` (`name`, `type`, `price`, `time`) VALUES ('Full leg & Bikini Waxing', 'Hair removal', 22, 1);
INSERT INTO `services` (`name`, `type`, `price`, `time`) VALUES ('Full leg', 'Hair removal', 15, 1);
INSERT INTO `services` (`name`, `type`, `price`, `time`) VALUES ('Bikini Waxing', 'Hair removal', 10, 1);