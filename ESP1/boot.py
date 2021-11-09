# This file is executed on every boot (including wake-boot from deepsleep)
import uos, machine
import gc

gc.collect()

# Deklaracja funkcji do połączenia się z siecią WiFi po włączeniu urządzenia
# w pliku main trzeba będzie ją wywołać i podać ssid, i hasło sieci WiFi
def WifiConnect(ssid, password):
    import network
    sta_if = network.WLAN(network.STA_IF)
    if not sta_if.isconnected():
        print('Connecting to wifi...')
        sta_if.active(True)
        sta_if.connect(ssid, passwd)
        while not sta_if.isconnected():
            pass
    print('Wifi Connected, IP Configuration:', sta_if.ifconfig())