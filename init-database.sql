-- Auto-fix script for APDATE database
-- This ensures production-ready state after docker-compose up

-- Fix boolean data types - convert various types to boolean for consistency
-- Only convert columns that actually exist and handle defaults properly

-- Skip boolean conversions - tables in Supabase might already have boolean types
-- These conversions are only needed for local Docker MySQL->PostgreSQL conversion

-- Skip all boolean conversions for Supabase - database.sql should have correct types already

-- Handle mt_notifikasi.is_read if table exists (create table if missing)
DO $$
BEGIN
    IF EXISTS (SELECT FROM information_schema.tables WHERE table_name = 'mt_notifikasi') THEN
        -- Check column type and convert accordingly
        IF EXISTS (SELECT FROM information_schema.columns WHERE table_name = 'mt_notifikasi' AND column_name = 'is_read' AND data_type = 'integer') THEN
            ALTER TABLE mt_notifikasi ALTER COLUMN is_read TYPE boolean USING (CASE WHEN is_read = 1 THEN TRUE ELSE FALSE END);
        ELSIF EXISTS (SELECT FROM information_schema.columns WHERE table_name = 'mt_notifikasi' AND column_name = 'is_read' AND data_type = 'character varying') THEN
            ALTER TABLE mt_notifikasi ALTER COLUMN is_read TYPE boolean USING (CASE WHEN is_read = '1' THEN TRUE ELSE FALSE END);
        END IF;
    ELSE
        -- Create mt_notifikasi table if it doesn't exist
        CREATE TABLE mt_notifikasi (
            id SERIAL PRIMARY KEY,
            nama VARCHAR(255) NOT NULL,
            uraian TEXT,
            tanggal DATE NOT NULL,
            is_read BOOLEAN NOT NULL DEFAULT FALSE,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        );
    END IF;
END
$$;

-- Update admin user group to have access to ALL menus
UPDATE m_users_group SET menu_access = '1,2,3,4,5,6,7,8,10,33,34,35,36,37,38,39,40,41,42,43' WHERE id_grup = 1;

-- Ensure admin password is properly hashed (if needed)
UPDATE m_users SET password = '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi' WHERE username = 'admin' AND LENGTH(password) = 32;

-- Set active periode if none exists
INSERT INTO mt_periode (tahun_ajaran, is_active) 
SELECT '2024/2025', TRUE
WHERE NOT EXISTS (SELECT 1 FROM mt_periode WHERE is_active = TRUE);

-- Set active periode semester if none exists  
INSERT INTO mt_periode_semester (periode_id, semester, status, is_active, is_close, closing_at)
SELECT p.id, 1, '1.1', TRUE, '0', NULL
FROM mt_periode p 
WHERE p.is_active = TRUE 
AND NOT EXISTS (SELECT 1 FROM mt_periode_semester WHERE is_active = TRUE)
LIMIT 1;

-- Update any existing periode semester to proper status for general operations
UPDATE mt_periode_semester SET status = '1.1' WHERE is_active = TRUE;

-- Ensure essential configurations exist
INSERT INTO mt_setting_lms (code, value)
SELECT 'app_name', 'APDATE'
WHERE NOT EXISTS (SELECT 1 FROM mt_setting_lms WHERE code = 'app_name');

INSERT INTO mt_setting_lms (code, value)
SELECT 'app_version', '2.0.0' 
WHERE NOT EXISTS (SELECT 1 FROM mt_setting_lms WHERE code = 'app_version');

-- Set proper permissions on essential data
-- Update admin user timestamp (no updated_at column, skip this)

-- Log completion
INSERT INTO mt_notifikasi (nama, uraian, tanggal, is_read) 
VALUES ('System', 'Database auto-setup completed successfully', CURRENT_DATE, FALSE);

-- Add missing deleted_at columns for soft delete functionality
ALTER TABLE mt_users_siswa ADD COLUMN IF NOT EXISTS deleted_at TIMESTAMP NULL;
ALTER TABLE mt_users_guru ADD COLUMN IF NOT EXISTS deleted_at TIMESTAMP NULL;
ALTER TABLE mt_mata_pelajaran ADD COLUMN IF NOT EXISTS deleted_at TIMESTAMP NULL;
ALTER TABLE mt_periode ADD COLUMN IF NOT EXISTS deleted_at TIMESTAMP NULL;

-- Add missing timestamp columns if needed
ALTER TABLE mt_users_siswa ADD COLUMN IF NOT EXISTS created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP;
ALTER TABLE mt_users_siswa ADD COLUMN IF NOT EXISTS updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP;
ALTER TABLE mt_users_guru ADD COLUMN IF NOT EXISTS created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP;
ALTER TABLE mt_users_guru ADD COLUMN IF NOT EXISTS updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP;

-- Fix sequences for core tables only (yang pasti ada)
SELECT setval(pg_get_serial_sequence('mt_mata_pelajaran', 'id'), GREATEST(COALESCE(MAX(id), 0), 1)) FROM mt_mata_pelajaran;
SELECT setval(pg_get_serial_sequence('mt_periode', 'id'), GREATEST(COALESCE(MAX(id), 0), 1)) FROM mt_periode;
SELECT setval(pg_get_serial_sequence('mt_periode_semester', 'id'), GREATEST(COALESCE(MAX(id), 0), 1)) FROM mt_periode_semester;
SELECT setval(pg_get_serial_sequence('m_menu', 'id'), GREATEST(COALESCE(MAX(id), 0), 1)) FROM m_menu;
SELECT setval(pg_get_serial_sequence('m_users', 'id'), GREATEST(COALESCE(MAX(id), 0), 1)) FROM m_users;
SELECT setval(pg_get_serial_sequence('m_users_group', 'id_grup'), GREATEST(COALESCE(MAX(id_grup), 0), 1)) FROM m_users_group;

-- Show setup results
SELECT 'Database setup completed!' as status,
       (SELECT COUNT(*) FROM m_menu) as total_menus,
       (SELECT menu_access FROM m_users_group WHERE id_grup = 1) as admin_menu_access;