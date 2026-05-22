import gdown
import os

url = 'https://drive.google.com/drive/folders/1soyRrzgss65sm56ipR11WbLypKe6yCIo?usp=sharing'
output_dir = 'scratch/drive_download'
os.makedirs(output_dir, exist_ok=True)

print("Starting Google Drive folder download...")
try:
    downloaded = gdown.download_folder(url, output=output_dir, quiet=False, use_cookies=False)
    print("Download completed successfully!")
    print("Files downloaded:", downloaded)
    
    # List downloaded folder contents
    for root, dirs, files in os.walk(output_dir):
        print(f"\nDirectory: {root}")
        print("Subdirectories:", dirs)
        print("Files:", files)
except Exception as e:
    print("An error occurred during download:", str(e))
