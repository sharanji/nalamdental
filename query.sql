TRUNCATE `emp_designations`;
TRUNCATE `org_roles`;


ALTER TABLE `qualification` ADD INDEX(`qualification_id`);

ALTER TABLE `qualification` ADD INDEX(`active_flag`);

ALTER TABLE `qualification` ADD `created_by` INT(11) NULL DEFAULT NULL AFTER `active_flag`, ADD `created_date` DATETIME NULL DEFAULT NULL AFTER `created_by`, ADD `last_updated_by` INT(11) NULL DEFAULT NULL AFTER `created_date`, ADD `last_updated_date` DATETIME NULL DEFAULT NULL AFTER `last_updated_by`;
ALTER TABLE `emp_designations` CHANGE `designation_id` `designation_id` INT(11) NOT NULL AUTO_INCREMENT, add PRIMARY KEY (`designation_id`);

ALTER TABLE `emp_designations` ADD INDEX(`designation_id`, `designation_name`, `designation_status`, `created_by`, `created_date`);

ALTER TABLE `emp_designations` ADD `created_by` INT(11) NULL DEFAULT NULL AFTER `designation_status`, ADD `created_date` DATETIME NULL DEFAULT NULL AFTER `created_by`, ADD `last_updated_by` INT(11) NULL DEFAULT NULL AFTER `created_date`, ADD `last_updated_date` DATETIME NULL DEFAULT NULL AFTER `last_updated_by`;

ALTER TABLE `org_roles` CHANGE `role_id` `role_id` INT(11) NOT NULL AUTO_INCREMENT, add PRIMARY KEY (`role_id`);

ALTER TABLE `org_roles` ADD INDEX(`role_id`, `role_code`, `role_name`, `role_status`, `active_flag`, `created_by`, `created_date`, `last_updated_by`, `last_updated_date`);

ALTER TABLE `org_roles`
  DROP `organization_id`,
  DROP `branch_id`;













-- Truncate Queries:

TRUNCATE banner;
TRUNCATE blogs;
TRUNCATE careers;
TRUNCATE contact_us;
TRUNCATE inv_categories;
TRUNCATE org_applied_jobs;
TRUNCATE org_jobs;
TRUNCATE org_job_category;
TRUNCATE services;
TRUNCATE reviews_headers;
TRUNCATE reviews_lines;