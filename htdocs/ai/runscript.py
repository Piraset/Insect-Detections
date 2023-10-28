import time
import subprocess
from datetime import datetime

# กำหนดเวลาเริ่มต้น (11:20) และเวลาสิ้นสุด (07:00 ในวันถัดไป) ในรูปแบบนาที
start_time = 18 * 60 + 10  # 11:20 ในรูปแบบนาที
end_time = 7 * 60  # 07:00 ในรูปแบบนาที (ในวันถัดไป)

# ชื่อไฟล์ Python ที่คุณต้องการรัน
script_name = "C:/xampp/htdocs/ai/Munseen1.py"

while True:
    current_time = datetime.now().time()
    current_minutes = current_time.hour * 60 + current_time.minute

    # ตรวจสอบว่าเวลาปัจจุบันอยู่ในช่วงเวลาที่คุณต้องการรัน
    if current_minutes >= start_time or current_minutes < end_time:
        # รันสคริปต์ Munseen.py ที่คุณต้องการ
        subprocess.run(["python", script_name])

    # รอเวลา 1 นาที
    time.sleep(3600)  # รอเวลา 1 นาที
