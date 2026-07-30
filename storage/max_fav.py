
from PIL import Image

img = Image.open(r'C:/Users/ASUS/.gemini/antigravity-ide/brain/5029ac20-c989-400f-9893-7dc37afb58b5/media__1785384148561.png').convert('RGBA')

bbox = img.getbbox()
if bbox:
    cropped = img.crop(bbox)
    w, h = cropped.size
    side = max(w, h)
    
    square = Image.new('RGBA', (side, side), (0, 0, 0, 0))
    square.paste(cropped, ((side - w) // 2, (side - h) // 2))
    
    final_img = square.resize((256, 256), Image.Resampling.LANCZOS)
    
    for dst in ['favicon-kk.png', 'favicon.ico', 'favicon.png', 'apple-touch-icon.png']:
        final_img.save(r'D:\Internship\Company-Page\klinik-komputer\public/' + dst)

print('MAX_ENLARGED_FAVICON_SUCCESS')
