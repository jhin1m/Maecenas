const token = $('meta[name="csrf-token"]').attr("content");

function execAjax(url, dataInput, modal) {
    $.ajax({
        url: url,
        type: "POST",
        data: {
            dataInput: dataInput,
            _token: token,
        },
        success: function (response) {
            $(modal).modal("hide");
            alert(response.message);
            window.location.reload();
        },
        error: function (xhr) {
            alert(xhr.responseText);
        },
    });
}
