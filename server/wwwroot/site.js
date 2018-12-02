const uri = "api/spaces";
let spaces = null;
function getCount(data) {
    const el = $("#counter");
    let name = "space";
    if (data) {
        if (data > 1) {
            name = "spaces";
        }
        el.text(data + " " + name);
    } else {
        el.html(`No ${name}`);
    }
}

function getEmptyCount(data) {
    const el = $("#empty-counter");
    let name = "space";
    if (data) {
        if (data > 1) {
            name = "spaces";
        }
        el.text(data + " empty " + name);
    } else {
        el.html(`No empty ${name}`);
    }
}

$(document).ready(function () {
    getData();
});

function getData() {
    $.ajax({
        type: "GET",
        url: uri,
        success: function (data) {
            $("#spaces").empty();
            getCount(data.length);

            var count = 0;
            $(data).each(function (key, item) {
                count++;
                if (item.space_status_id === 0) $("#" + count).addClass("bg-success");
                else $("#" + count).addClass("bg-danger");
            });


            var numEmpty = 0;
            $(data).each(function (key, item) {
                if (item.space_status_id === 0) numEmpty++;
            });
            getEmptyCount(numEmpty);
            spaces = data;
        }
    });
}
