<!DOCTYPE html>
<html>
<head>
    <title>現在日時表示</title>
</head>
<body>
    <!-- 日時を表示する要素 -->
    <div id="currentDateTime"></div>

    <script>
        // 現在の日時を扱うクラス
        class DateTimeDisplay {
            constructor(elementId) {
                this.element = document.getElementById(elementId);
                this.updateDateTime();
                this.startUpdating();
            }

            // 現在の日時を更新して表示
            updateDateTime() {
                const now = new Date();
                const formattedDateTime = now.toLocaleString();
                this.element.textContent = formattedDateTime;
            }

            // 1秒ごとに日時を更新
            startUpdating() {
                setInterval(() => {
                    this.updateDateTime();
                }, 1000);
            }
        }

        // 初期化
        const dateTimeDisplay = new DateTimeDisplay("currentDateTime");
    </script>
</body>
</html>
