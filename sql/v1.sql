alter table quotation add column deleted boolean DEFAULT false;
alter table quotation modify location CHAR(60);
