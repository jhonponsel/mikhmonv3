function RequiredV() {
    var e = document.getElementById("expmode").value,
        t = document.getElementById("validity").style,
        l = document.getElementById("validi"),
        rt = document.getElementById("tlgrm").style;

    "rem" === e || "remc" === e ? (t.display = "table-row", l.type = "text", "" === l.value && (l.value = ""), $("#validi").focus(), rt.display = "table-row") : "ntf" === e || "ntfc" === e ? (t.display = "table-row", rt.display = "table-row", l.type = "text", "" === l.value && (l.value = ""), $("#validi").focus(), t.display = "none") : (t.display = "none", l.type = "hidden", rt.display = "none")
}

function defUserl() {
    var e = document.getElementById("user").value,
        l = document.getElementById("num").style,
        t = document.getElementById("lower").style,
        d = document.getElementById("upper").style,
        n = document.getElementById("upplow").style,
        o = document.getElementById("lower1").style,
        y = document.getElementById("upper1").style,
        a = document.getElementById("upplow1").style,
        i = document.getElementById("mix").style,
        s = document.getElementById("mix1").style,
        m = document.getElementById("mix2").style;
    "up" === e ? ($("select[name=userl] option:first").html("4"), $("select[name=char] option:first").html("Random abcd"), t.display = "block", d.display = "block", n.display = "block", o.display = "none", y.display = "none", a.display = "none", l.display = "none", i.display = "block", s.display = "block", m.display = "block") : "vc" === e && ($("select[name=userl] option:first").html("8"), $("select[name=char] option:first").html("Random abcd2345"), t.display = "none", d.display = "none", n.display = "none", o.display = "block", y.display = "block", a.display = "block", l.display = "block", i.display = "block", s.display = "block", m.display = "block")
}

function loader() {
    document.getElementById('loader').style = 'display:inline;';
}

function notify(msg) {
    var notify = $("#notify");
    notify.find(".message").text(msg);
    notify.show();
    var originalText = $(".message").text(),
        i = 0;
    setInterval(function () {
        $(".message").append("●");
        i++;
        if (i == 4) {
            $(".message").html(originalText);
            i = 0;
        }
    }, 500);
}

function printBT() {
    window.location = "my.bluetoothprint.scheme://";
}

function connect(sess) {
    $("#temp").load("./admin.php?id=connect&session=" + sess);
}

function stheme(thm) {
    $("#temp").load(thm);
}

function dellSelected(sel) {
    $("#temp").load(sel);
}

function loadpage(page) {
    $("#temp").load(page);
}
$('.main-container').fadeIn(400);
$("#loading").hide();

function sortTable(t, e, o) {
    var l, n = t.tBodies[0],
        r = Array.prototype.slice.call(n.rows, 0);
    for (o = -(+o || -1), r = r.sort(function (t, l) {
            return o * t.cells[e].textContent.trim().localeCompare(l.cells[e].textContent.trim())
        }), l = 0; l < r.length; ++l) n.appendChild(r[l])
}

function makeSortable(t) {
    var e, o = t.tHead;
    if (o && (o = o.rows[0]) && (o = o.cells), o)
        for (e = o.length; --e >= 0;) ! function (e) {
            var l = 1;
            o[e].addEventListener("click", function () {
                sortTable(t, e, l = 1 - l)
            })
        }(e)
}

function makeAllSortable(t) {
    for (var e = (t = t || document.body).getElementsByTagName("table"), o = e.length; --o >= 0;) makeSortable(e[o])
}

function TreeChange() {
    var pu = document.getElementById('parent_tree_u').value,
        limatu = document.getElementById('limat_u').style,
        limaxu = document.getElementById('limax_u').style;

    '' === pu || 'none' === pu ? (limatu.display = 'none', limaxu.display = 'none') : (limatu.display = 'table-row', limaxu.display = 'table-row')
}

function nwReportMode() {
    var r = document.getElementById("reportmode").value,
        s = document.getElementById("upf").style,
        t = document.getElementById("downf").style,
        u = document.getElementById("tlgrminfo").style;

    if(r=="telegram"){
        s.display = "none";
        t.display = "none";
        u.display = "table-row";
    }else if(r=="manual"){
        s.display = "table-row";
        t.display = "table-row";
        u.display = "none";
    }else{
        s.display = "none";
        t.display = "none";
        u.display = "none";
    }
}