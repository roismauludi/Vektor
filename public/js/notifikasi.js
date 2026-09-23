/**
 * VEKTOR - Sistem Notifikasi Real-Time
 * File ini JANGAN dihapus atau diubah.
 * Di-load dari base.blade.php
 */
window.addEventListener("load", function () {
    if (!window.__NOTIF_CSRF || !window.__NOTIF_URL) {
        console.warn("[NOTIF] Config tidak ditemukan.");
        return;
    }

    var POLL_INTERVAL = 15000;
    var CSRF_TOKEN    = window.__NOTIF_CSRF;
    var UNREAD_URL    = window.__NOTIF_URL;

    console.log("[NOTIF] Sistem notifikasi aktif. Polling tiap 15 detik. URL:", UNREAD_URL);

    function makeToast() {
        if (typeof Swal === "undefined") return null;
        return Swal.mixin({
            toast: true,
            position: "top-end",
            showConfirmButton: false,
            timer: 7000,
            timerProgressBar: true,
            didOpen: function(toast) {
                toast.addEventListener("mouseenter", Swal.stopTimer);
                toast.addEventListener("mouseleave", Swal.resumeTimer);
            }
        });
    }

    function markRead(id) {
        fetch("/notifications/" + id + "/read", {
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": CSRF_TOKEN,
                "Accept": "application/json",
                "Content-Type": "application/json"
            }
        }).catch(function() {});
    }

    function showNotification(notif) {
        var icon  = "info";
        var bg    = "#fff7ed";
        var color = "#c2410c";

        if (notif.type === "pengajuan_diverifikasi") {
            if (notif.data && notif.data.status === "approved") {
                icon  = "success"; bg = "#f0fdf4"; color = "#15803d";
            } else {
                icon  = "error"; bg = "#fef2f2"; color = "#b91c1c";
            }
        }

        console.log("[NOTIF] Tampilkan:", notif.title, "|", notif.message);

        var Toast = makeToast();
        if (Toast) {
            Toast.fire({
                icon: icon,
                title: "<span style=\"color:" + color + ";font-weight:700;font-size:0.95rem\">" + notif.title + "</span>",
                html: "<span style=\"color:#374151;font-size:0.85rem\">" + notif.message + "</span>",
                background: bg,
                iconColor: color,
            });
        } else {
            var div = document.createElement("div");
            div.setAttribute("style",
                "position:fixed;top:20px;right:20px;z-index:99999;" +
                "background:" + bg + ";border-left:4px solid " + color + ";" +
                "padding:14px 18px;border-radius:8px;box-shadow:0 4px 15px rgba(0,0,0,.15);" +
                "max-width:320px;font-family:sans-serif;cursor:pointer;"
            );
            div.innerHTML = "<strong style=\"color:" + color + ";display:block;margin-bottom:4px\">" + notif.title + "</strong>" +
                "<span style=\"color:#374151;font-size:0.85rem\">" + notif.message + "</span>";
            div.onclick = function() { if (document.body.contains(div)) document.body.removeChild(div); };
            document.body.appendChild(div);
            setTimeout(function() { if (document.body.contains(div)) document.body.removeChild(div); }, 7000);
        }

        markRead(notif.id);
    }

    function pollNotifications() {
        fetch(UNREAD_URL, {
            headers: { "Accept": "application/json", "X-CSRF-TOKEN": CSRF_TOKEN }
        })
        .then(function(res) {
            if (!res.ok) { console.warn("[NOTIF] Fetch gagal, status:", res.status); return []; }
            return res.json();
        })
        .then(function(notifications) {
            if (notifications.length > 0) {
                console.log("[NOTIF] Ada " + notifications.length + " notifikasi baru.");
                notifications.forEach(function(notif) { showNotification(notif); });
            }
        })
        .catch(function(err) { console.warn("[NOTIF] Error:", err); });
    }

    // Polling pertama setelah 2 detik, lalu setiap 15 detik
    setTimeout(pollNotifications, 2000);
    setInterval(pollNotifications, POLL_INTERVAL);
});
