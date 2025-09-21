<?php
	class Akademik_model extends CI_Model{

		public function find_mapel_siswa($semester_id, $kelas_id, $siswa_id) {
			$query = "
				SELECT
					a.id,
					COALESCE(a.jumlah_pertemuan, 0) as jumlah_pertemuan,
					c.code as mapel_code,
					c.name as mapel_name,
					d.nip as guru_code,
					d.nama as guru_name
				FROM tref_kelas_jadwal_pelajaran a
				INNER JOIN mt_mata_pelajaran c ON a.mata_pelajaran_id = c.id
				INNER JOIN mt_users_guru d ON a.guru_id = d.id
				INNER JOIN tref_kelas_siswa e ON a.kelas_id = e.kelas_id AND e.status = 'aktif'
				WHERE
					a.semester_id = ? AND
					a.kelas_id = ? AND
					e.siswa_id = ?
			";
			$result = $this->db->query($query, [$semester_id, $kelas_id, $siswa_id])->result_array();
			return $result;
		}

		public function find_semester_siswa($siswa_id) {
			$query = "
				SELECT
					a.id,
					a.tahun_ajaran
				FROM mt_periode as a
				INNER JOIN tref_kelas b ON a.id = b.periode_id
				INNER JOIN tref_kelas_siswa c ON b.id = c.kelas_id
				WHERE
					c.siswa_id = ?
				ORDER BY a.tahun_ajaran DESC, a.id DESC
			";
			$result = $this->db->query($query, [$siswa_id])->result_array();
			return $result;
		}

		public function list_nilai_semester($semester_id, $siswa_id) {
			$query = "
				SELECT
						a.*,
						d.code as mapel_code,
						d.name as mapel_name,
						(SELECT COUNT(id) FROM tref_pertemuan_absensi WHERE jadwal_kelas_id = c.id AND siswa_id = a.siswa_id AND status_kehadiran = 'hadir') as absensi_kehadiran,
						(
							SELECT FLOOR(AVG(nilai) + 0.5) 
							FROM tref_pertemuan_tugas sub_a 
							INNER JOIN tref_pertemuan sub_b ON sub_a.pertemuan_id = sub_b.id
							WHERE 
								sub_a.jadwal_kelas_id = c.id AND
								sub_a.siswa_id = a.siswa_id AND
								sub_b.pertemuan_ke NOT IN ('UTS', 'UAS')
						) as nilai_tugas,
						(
							SELECT nilai
							FROM tref_pertemuan_tugas sub_a 
							INNER JOIN tref_pertemuan sub_b ON sub_a.pertemuan_id = sub_b.id
							WHERE 
								sub_a.jadwal_kelas_id = c.id AND
								sub_a.siswa_id = a.siswa_id AND
								sub_b.pertemuan_ke = 'UTS'
						) as nilai_uts,
						(
							SELECT nilai
							FROM tref_pertemuan_tugas sub_a 
							INNER JOIN tref_pertemuan sub_b ON sub_a.pertemuan_id = sub_b.id
							WHERE 
								sub_a.jadwal_kelas_id = c.id AND
								sub_a.siswa_id = a.siswa_id AND
								sub_b.pertemuan_ke = 'UAS'
						) as nilai_uas
				FROM 
						tref_kelas_siswa AS a
				INNER JOIN 
						tref_kelas_jadwal_pelajaran AS c ON a.kelas_id = c.kelas_id
				INNER JOIN 
						mt_mata_pelajaran AS d ON c.mata_pelajaran_id = d.id
				WHERE
						c.semester_id = ? AND a.siswa_id = ?
			";
			$result = $this->db->query($query, [$semester_id, $siswa_id])->result_array();
			return $result;
		}

		public function list_nilai_siswa($siswa_id) {
			$query = "
				SELECT
					a.*,
					c.code AS mapel_code,
					c.name AS mapel_name,
					d.kelas
				FROM tr_egrading_siswa AS a
				INNER JOIN
					tref_kelas_jadwal_pelajaran AS b ON a.jadwal_kelas_id = b.id
				INNER JOIN
					mt_mata_pelajaran AS c ON b.mata_pelajaran_id = c.id
				INNER JOIN
					tref_kelas AS d ON b.kelas_id = d.id
				WHERE
					a.siswa_id = ?
				ORDER BY d.kelas, c.name ASC
			";

			$result = $this->db->query($query, [$siswa_id])->result_array();
			return $result;
		}
	}
?>