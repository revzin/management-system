DISC
SPOOL C:\revzin_install.log

PROMPT ======== ��������� ���� ������ ��� (�. ������, ��4-82) =========

PROMPT ==== ���� � ���� ====
@@start_session.sql
PROMPT ==== ���� ������� ====

PROMPT ==== �������� �� ====
@@drop_everything.sql
PROMPT ==== �� ������� ====

PROMPT ==== �������� ����������� ������ ====
@@create_tables.sql
PROMPT ==== ����������� ������ ������� ====

PROMPT ==== �������� ����������� ====
@@create_constraints.sql
PROMPT ==== ����������� ������� ====

PROMPT ==== �������� ��������� �������������� ====
@@create_triggers.sql
PROMPT ==== �������� �������������� ������� ====

PROMPT ==== �������� ���������� ��� �� ��������� ====
@@create_default_employee.sql
PROMPT ==== ��������� ������, �����/������: system/manager ====

PROMPT ======== ���������� ��������� =========
COMMIT;

PROMPT ======== ��������� ��������� =========
DISC

SPOOL OFF
	