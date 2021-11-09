from machine import Pin, SoftI2C
import bme280, ssd1306
from time import sleep


i2c =SoftI2C(scl=Pin(22), sda=Pin(21), freq = 400000)
bme = bme280.BME280(i2c=i2c, address=0x76)
oled = ssd1306.SSD1306_I2C(128, 64, i2c, 0x3c)

print('Running...')

# Wymaga zdefiniowania WifiConnect również w boot.py
def WifiConnect(ssid, passwd):
    import network
    sta_if = network.WLAN(network.STA_IF)
    if not sta_if.isconnected():
        print('Connecting to wifi...')
        sta_if.active(True)
        sta_if.connect(ssid, passwd)
        while not sta_if.isconnected():
            pass
    print('Wifi Connected, IP Configuration:', sta_if.ifconfig())
WifiConnect('MW70VK_F8******', '**********')     # dane połączenia

time.sleep(1)
url = 'https://student.agh.edu.pl/~dziarmag/post-data.php'
headers={'Content-Type': 'application/x-www-form-urlencoded'}  # nagłówek niezbędny do prawidłowego odczytania przez skrypt php
api='*************'     # api compatible with the one in php script

while True:
    if oled:
        oled.fill(0)
        oled.text('Dane pogodowe:',1,1,1)
        oled.text(str(bme.values[0]),1,10,1)
        oled.text(str(bme.values[1]),1,20,1)
        oled.text(str(bme.values[2]),1,30,1)
        oled.show()
    print('\n')
    print(str(bme.values[0]) + '  ' + str(bme.values[1])+ '  ' + str(bme.values[2]))
    
    temperature=bme.values[0][:-1]
    pressure=bme.values[1][:-3]
    humidity=bme.values[2][:-1]
    userdata = 'api_key='+api+'&'+'value1='+temperature+'&'+'value2='+humidity+'&'+'value3='+pressure
    try:
        res = urequests.post(url=url, data=userdata, headers=headers)
        oled.text(res.text,1,50,1)
        oled.show()
        print(res.text)
        res.close()
    except:
        oled.fill(0)
        oled.text('ERROR',10,1,1)
        oled.show()
    time.sleep(56)