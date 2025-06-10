from zk import ZK
import mysql.connector
import time
from datetime import datetime

zk = ZK('192.168.1.201', port=4370, timeout=5)

db = mysql.connector.connect(
    host="localhost",
    user="root",
    password="",
    database="labit-sipresenta"
)
cursor = db.cursor()

def get_praktikan_by_uid(uid):
    cursor.execute("SELECT nim, nama, id_praktikan FROM praktikan WHERE uid = %s", (uid,))
    return cursor.fetchone()

def get_open_attendance(id_praktikan, id_pertemuan):
    cursor.execute("""
        SELECT id_presensi FROM rekap_praktikan
        WHERE id_praktikan = %s AND id_pertemuan = %s AND waktu_checkout IS NULL
    """, (id_praktikan, id_pertemuan))
    return cursor.fetchone()

def get_kelas_berjalan(hari, waktu_check):
    waktu_str = waktu_check.strftime("%H:%M:%S")
    cursor.execute("""
        SELECT id_kelas, matkul, kelas FROM kelas
        WHERE hari = %s
          AND waktu_mulai <= %s
          AND waktu_selesai >= %s
        LIMIT 1
    """, (hari, waktu_str, waktu_str))
    return cursor.fetchone()

def get_id_kelas_from_praktikan(id_praktikan, id_kelas_berjalan):
    cursor.execute("""
        SELECT id_kelas FROM kelas_praktikan
        WHERE id_praktikan = %s AND id_kelas = %s
        LIMIT 1
    """, (id_praktikan, id_kelas_berjalan))
    result = cursor.fetchone()
    return result[0] if result else None

def get_id_pertemuan_berjalan(id_kelas, waktu_check):
    tanggal_str = waktu_check.date()
    print(f"DEBUG: Cari pertemuan dengan id_kelas={id_kelas}, tanggal={tanggal_str}")
    cursor.execute("""
        SELECT id_pertemuan FROM pertemuan
        WHERE id_kelas = %s
          AND tanggal = %s
        LIMIT 1
    """, (id_kelas, tanggal_str))
    result = cursor.fetchone()
    return result[0] if result else None

def already_logged(id_praktikan, id_pertemuan, waktu_checkin):
    cursor.execute("""
        SELECT COUNT(*) FROM rekap_praktikan
        WHERE id_praktikan = %s AND id_pertemuan = %s AND waktu_checkin = %s
    """, (id_praktikan, id_pertemuan, waktu_checkin))
    return cursor.fetchone()[0] > 0

hari_map = {
    'Monday': 'Senin',
    'Tuesday': 'Selasa',
    'Wednesday': 'Rabu',
    'Thursday': 'Kamis',
    'Friday': 'Jumat',
    'Saturday': 'Sabtu',
    'Sunday': 'Minggu'
}

try:
    print("Menghubungkan ke fingerprint...")
    conn = zk.connect()
    conn.disable_device()
    print("Tersambung!")

    last_processed_time = None

    while True:
        attendance_list = conn.get_attendance()

        if not attendance_list:
            time.sleep(2)
            continue

        last_att = attendance_list[-1]
        uid = str(last_att.user_id)
        waktu = last_att.timestamp
        today = waktu.date()

        if last_processed_time == waktu:
            time.sleep(2)
            continue
        last_processed_time = waktu

        hari_inggris = waktu.strftime("%A")
        hari_str = hari_map.get(hari_inggris)
        if not hari_str:
            print(f"Hari tidak dikenali: {hari_inggris}")
            continue

        praktikan = get_praktikan_by_uid(uid)
        if not praktikan:
            print(f"UID {uid} tidak ditemukan.")
            continue

        nim, nama, id_praktikan = praktikan

        kelas_berjalan = get_kelas_berjalan(hari_str, waktu)
        if not kelas_berjalan:
            print(f"Tidak ada kelas aktif saat itu untuk {nama} ({nim})")
            continue

        id_kelas_berjalan, matkul, kelas_nama = kelas_berjalan
        id_kelas = get_id_kelas_from_praktikan(id_praktikan, id_kelas_berjalan)

        if not id_kelas:
            print(f"{nama} ({nim}) tidak terdaftar di kelas {kelas_nama}")
            continue

        id_pertemuan = get_id_pertemuan_berjalan(id_kelas, waktu)
        if not id_pertemuan:
            print(f"Tidak ada pertemuan berjalan untuk kelas {kelas_nama} pada waktu {waktu}")
            continue

        if already_logged(id_praktikan, id_pertemuan, waktu):
            print(f"{nama} ({nim}) sudah tercatat pada pertemuan {id_pertemuan} waktu {waktu}, abaikan.")
            continue

        open_record = get_open_attendance(id_praktikan, id_pertemuan)

        if open_record:
            rek_id = open_record[0]
            cursor.execute("""
                UPDATE rekap_praktikan
                SET waktu_checkout = %s, keterangan = 'Hadir'
                WHERE id_presensi = %s
            """, (waktu, rek_id))
            db.commit()
            print(f"{nama} ({nim}) checkout pada {waktu}, Matkul: {matkul}, Kelas: {kelas_nama}, Pertemuan ID: {id_pertemuan}")
        else:
            cursor.execute("""
                INSERT INTO rekap_praktikan (id_praktikan, id_kelas, id_pertemuan, waktu_checkin, keterangan)
                VALUES (%s, %s, %s, %s, 'Masuk')
            """, (id_praktikan, id_kelas, id_pertemuan, waktu))
            db.commit()
            print(f"{nama} ({nim}) checkin pada {waktu}, Matkul: {matkul}, Kelas: {kelas_nama}, Pertemuan ID: {id_pertemuan}")

        time.sleep(2)

except Exception as e:
    print("Terjadi error:", e)

finally:
    if conn:
        conn.enable_device()
        conn.disconnect()
        print("Koneksi fingerprint ditutup.")
    cursor.close()
    db.close()