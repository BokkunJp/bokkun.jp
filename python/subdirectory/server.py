#!C:/Users/bokku/AppData/Local/Programs/Python/Python310/python.exe
import tkinter as tk
import urllib.parse
print("Content-Type: text/html;\n")

# 入力データ
inData = urllib.parse.unquote(input())
root = tk.Tk()
root.title('Test')
root.geometry("400x100")
label = tk.Label(root, text = inData, bg = 'gold')
label.pack()
root.mainloop()