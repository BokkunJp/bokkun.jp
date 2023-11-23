#インポート
import sys
import os
import pandas as pd
import matplotlib.pyplot as plt
#定義部
def please(printValue):
    print(printValue)

def buy(money):
    print(money + "円お買い上げありがとうございます。")
# ここまでが定義部

# メイン処理
please("お買い上げいただく金額を入力してください。")
count = 0
while (True):
    if (count >= 5):
        print( "エラーが所定の回数を超えました。")
        break
    money = input()
    if (not money.isdecimal()):
        print("正しい金額を入力してください。")
        count += 1
    else:
        break;

if (count < 5):
    buy(money)