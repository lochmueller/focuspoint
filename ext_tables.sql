#
# Table structure for table 'tx_focuspoint_domain_model_dimension'
#
CREATE TABLE tx_focuspoint_domain_model_dimension (
    uid int(11) NOT NULL auto_increment,
    pid int(11) DEFAULT '0' NOT NULL,
    tstamp int(11) DEFAULT '0' NOT NULL,
    crdate int(11) DEFAULT '0' NOT NULL,
    cruser_id int(11) DEFAULT '0' NOT NULL,
    deleted tinyint(4) DEFAULT '0' NOT NULL,

    title varchar(255) DEFAULT '' NOT NULL,
    identifier varchar(255) DEFAULT '' NOT NULL,
    dimension varchar(255) DEFAULT '' NOT NULL,

    PRIMARY KEY (uid),
    KEY parent (pid)
);

#
# Table structure for table 'tx_focuspoint_domain_model_filestandalone'
#
CREATE TABLE tx_focuspoint_domain_model_filestandalone (
    uid int(11) NOT NULL auto_increment,
    pid int(11) DEFAULT '0' NOT NULL,
    tstamp int(11) DEFAULT '0' NOT NULL,
    crdate int(11) DEFAULT '0' NOT NULL,
    cruser_id int(11) DEFAULT '0' NOT NULL,
    deleted tinyint(4) DEFAULT '0' NOT NULL,

    relative_file_path text,
    focus_point_x int(11) DEFAULT NULL,
    focus_point_y int(11) DEFAULT NULL,

    PRIMARY KEY (uid),
    KEY parent (pid)
);

#
# Table structure for table 'tt_content'
#
CREATE TABLE tt_content (
    image_ratio varchar(50) DEFAULT '' NOT NULL
);

#
# Table structure for table 'sys_file_reference'
#
CREATE TABLE sys_file_reference (
    focus_point_x int(11) DEFAULT NULL,
    focus_point_y int(11) DEFAULT NULL
);

#
# Table structure for table 'sys_file_metadata'
#
CREATE TABLE sys_file_metadata (
    focus_point_x int(11) null,
    focus_point_y int(11) null,
);
