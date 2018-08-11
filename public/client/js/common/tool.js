// 表示をエスケープする関数
function htmlspecialchars(ch) {
    console.log(ch);
    ch = ch.replace(/&/g, "&amp;");
    ch = ch.replace(/"/g, "&quot;");
    ch = ch.replace(/'/g, "&#039;");
    ch = ch.replace(/</g, "&lt;");
    ch = ch.replace(/>/g, "&gt;");
    return ch;
}