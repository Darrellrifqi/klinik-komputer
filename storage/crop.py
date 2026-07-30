
from PIL import Image

img_path = r'C:/Users/ASUS/.gemini/antigravity-ide/brain/5029ac20-c989-400f-9893-7dc37afb58b5/media__1785384298053.png'
img = Image.open(img_path).convert('RGBA')

bbox = img.getbbox()
if bbox:
    cropped = img.crop(bbox)
    w, h = cropped.size
    side = max(w, h)
    new_img = Image.new('RGBA', (side, side), (0, 0, 0, 0))
    offset = ((side - w) // 2, (side - h) // 2)
    new_img.paste(cropped, offset)
    
    final_img = new_img.resize((128, 128), Image.Resampling.LANCZOS)
    
    dst1 = r'D:\Internship\Company-Page\klinik-komputer\public\favicon-kk.png'
    dst2 = r'D:\Internship\Company-Page\klinik-komputer\public\favicon.ico'
    dst3 = r'D:\Internship\Company-Page\klinik-komputer\public\favicon.png'
    dst4 = r'D:\Internship\Company-Page\klinik-komputer\public\apple-touch-icon.png'
    
    final_img.save(dst1)
    final_img.save(dst2)
    final_img.save(dst3)
    final_img.save(dst4)
    print('SUCCESS_PYTHON_CROP')
