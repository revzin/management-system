DISC

SPOOL C:\revzin_install.log

PROMPT ======== ��������� ���� ������ ��� �� (�. ������, ��4-82) =========

PROMPT ==== ���� � ���� ====
@@01_start_session.sql
PROMPT ==== ���� ������� ====

PROMPT ==== �������� �� ====
@@02_drop_tables_cascade.sql
PROMPT ==== �� ������� ====

PROMPT ==== �������� ����������� ������ ====
@@03_create_tables.sql
PROMPT ==== ����������� ������ ������� ====

PROMPT ==== �������� ����������� ====
@@04_create_constraints.sql
PROMPT ==== ����������� ������� ====

PROMPT ==== �������� ��������� �������������� ====
@@05_create_triggers.sql
PROMPT ==== �������� �������������� ������� ====

PROMPT === �������� �������� �������� ====
@@06_create_procedures.sql
PROMPT === ��������� ������� ====

PROMPT ==== �������� ���������� ��� �� ��������� ====
@@07_create_default_employee.sql
PROMPT ==== ��������� ������, �����/������: system/manager ====

PROMPT ======== ���������� ��������� =========
COMMIT;

PROMPT ======== ��������� ��������� =========
DISC

SPOOL OFF
	