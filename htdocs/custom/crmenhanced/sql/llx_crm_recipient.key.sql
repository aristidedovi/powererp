ALTER TABLE llx_crm_recipient ADD CONSTRAINT `fk_crm_recipient_fk_crm_messages` FOREIGN KEY (`fk_message`) REFERENCES `llx_crm_messages` (`rowid`);