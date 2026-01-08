$(function() {
    /* Visually impaired certificate */
    $("#scanned_vis_imp_cert").change(function() {

        var filesize2 = this.files[0].size / 1024 > 100;
        var flag = 1;
        var file, img;

        $('#p_photograph').hide();

        var photograph_image = document.getElementById('scanned_vis_imp_cert');
        var photograph_im = photograph_image.value;
        var ext1 = photograph_im.substring(photograph_im.lastIndexOf('.') + 1);

        if (photograph_image.value != "" && ext1 != 'jpg' && ext1 != 'JPG' && ext1 != 'jpeg' && ext1 != 'JPEG') {
            $('#error_vis_imp_cert').show();
            $('#error_vis_imp_cert').fadeIn(3000);
            document.getElementById('error_vis_imp_cert').innerHTML = "Upload JPG or jpg file only.";
            setTimeout(function() {
                $('#error_vis_imp_cert').css('color', '#B94A48');
                document.getElementById("scanned_vis_imp_cert").value = "";
                $('#hidden_vis_imp_cert').val('');
            }, 30);
            flag = 0;
            $(".vis_imp_cert_text").hide();
        } else if (filesize2) {
            $('#error_vis_imp_cert_size').show();
            $('#error_vis_imp_cert_size').fadeIn(3000);
            document.getElementById('error_vis_imp_cert_size').innerHTML = "File size should be maximum 100 KB.";
            setTimeout(function() {
                $('#error_vis_imp_cert_size').css('color', '#B94A48');
                document.getElementById("scanned_vis_imp_cert").value = "";
                $('#hidden_vis_imp_cert').val('');
            }, 30);
            flag = 0;
            $(".vis_imp_cert_text").hide();
        }

        if (flag == 1) {
            $('#error_vis_imp_cert').html('');
            $('#error_vis_imp_cert_size').html('');
            var files = !!this.files ? this.files : [];
            if (!files.length || !window.FileReader) return; // no file selected, or no FileReader support
            if (/^image/.test(files[0].type)) { // only image file
                var reader = new FileReader(); // instance of the FileReader
                reader.readAsDataURL(files[0]); // read the local file
                reader.onloadend = function() { // set image data as background of div
                    $('#hidden_vis_imp_cert').val(this.result);
                }
            }
            readURL(this, 'image_upload_vis_imp_cert_preview');
            return true;
        } else {
            return false;
        }
    });

    /* Orthopedically handicapped certificate */
    $("#scanned_orth_han_cert").change(function() {

        var filesize2 = this.files[0].size / 1024 > 100;
        var flag = 1;
        var file, img;

        $('#p_photograph').hide();

        var photograph_image = document.getElementById('scanned_orth_han_cert');
        var photograph_im = photograph_image.value;
        var ext1 = photograph_im.substring(photograph_im.lastIndexOf('.') + 1);

        if (photograph_image.value != "" && ext1 != 'jpg' && ext1 != 'JPG' && ext1 != 'jpeg' && ext1 != 'JPEG') {
            $('#error_orth_han_cert').show();
            $('#error_orth_han_cert').fadeIn(3000);
            document.getElementById('error_orth_han_cert').innerHTML = "Upload JPG or jpg file only.";
            setTimeout(function() {
                $('#error_orth_han_cert').css('color', '#B94A48');
                document.getElementById("scanned_orth_han_cert").value = "";
                $('#hidden_orth_han_cert').val('');
            }, 30);
            flag = 0;
            $(".orth_han_cert_text").hide();
        } else if (filesize2) {
            $('#error_orth_han_cert_size').show();
            $('#error_orth_han_cert_size').fadeIn(3000);
            document.getElementById('error_orth_han_cert_size').innerHTML = "File size should be maximum 100 KB.";
            setTimeout(function() {
                $('#error_orth_han_cert_size').css('color', '#B94A48');
                document.getElementById("scanned_orth_han_cert").value = "";
                $('#hidden_orth_han_cert').val('');
            }, 30);
            flag = 0;
            $(".orth_han_cert_text").hide();
        }

        if (flag == 1) {
            $('#error_orth_han_cert').html('');
            $('#error_orth_han_cert_size').html('');
            var files = !!this.files ? this.files : [];
            if (!files.length || !window.FileReader) return; // no file selected, or no FileReader support
            if (/^image/.test(files[0].type)) { // only image file
                var reader = new FileReader(); // instance of the FileReader
                reader.readAsDataURL(files[0]); // read the local file
                reader.onloadend = function() { // set image data as background of div
                    $('#hidden_orth_han_cert').val(this.result);
                }
            }
            readURL(this, 'image_upload_orth_han_cert_preview');
            return true;
        } else {
            return false;
        }
    });

    /* Cerebral palsy certificate */
    $("#scanned_cer_palsy_cert").change(function() {

        var filesize2 = this.files[0].size / 1024 > 100;
        var flag = 1;
        var file, img;

        $('#p_photograph').hide();

        var photograph_image = document.getElementById('scanned_cer_palsy_cert');
        var photograph_im = photograph_image.value;
        var ext1 = photograph_im.substring(photograph_im.lastIndexOf('.') + 1);

        if (photograph_image.value != "" && ext1 != 'jpg' && ext1 != 'JPG' && ext1 != 'jpeg' && ext1 != 'JPEG') {
            $('#error_cer_palsy_cert').show();
            $('#error_cer_palsy_cert').fadeIn(3000);
            document.getElementById('error_cer_palsy_cert').innerHTML = "Upload JPG or jpg file only.";
            setTimeout(function() {
                $('#error_cer_palsy_cert').css('color', '#B94A48');
                document.getElementById("scanned_cer_palsy_cert").value = "";
                $('#hidden_cer_palsy_cert').val('');
            }, 30);
            flag = 0;
            $(".cer_palsy_cert_text").hide();
        } else if (filesize2) {
            $('#error_cer_palsy_cert_size').show();
            $('#error_cer_palsy_cert_size').fadeIn(3000);
            document.getElementById('error_cer_palsy_cert_size').innerHTML = "File size should be maximum 100 KB.";
            setTimeout(function() {
                $('#error_cer_palsy_cert_size').css('color', '#B94A48');
                document.getElementById("scanned_cer_palsy_cert").value = "";
                $('#hidden_cer_palsy_cert').val('');
            }, 30);
            flag = 0;
            $(".cer_palsy_cert_text").hide();
        }

        if (flag == 1) {
            $('#error_cer_palsy_cert').html('');
            $('#error_cer_palsy_cert_size').html('');
            var files = !!this.files ? this.files : [];
            if (!files.length || !window.FileReader) return; // no file selected, or no FileReader support
            if (/^image/.test(files[0].type)) { // only image file
                var reader = new FileReader(); // instance of the FileReader
                reader.readAsDataURL(files[0]); // read the local file
                reader.onloadend = function() { // set image data as background of div
                    $('#hidden_cer_palsy_cert').val(this.result);
                }
            }
            readURL(this, 'image_upload_cer_palsy_cert_preview');
            return true;
        } else {
            return false;
        }
    });

});

if ($(".benchmark_disability_y").prop("checked")) {
    $("#benchmark_disability_div").show();
    $(".scribe_div").show();
}

/* Benchmark Disability */
$(document).ready(function() {

    $(document).on("click", ".benchmark_disability_y", function() {
        $("#benchmark_disability_div").show();
        $(".scribe_div").show();
    });

    $(document).on("click", ".benchmark_disability_n", function() {

        $("#scanned_vis_imp_cert").removeAttr("required");
        $("#scanned_vis_imp_cert").val("");
        $('.visually_impaired_n').prop('checked', 'checked');
        $("#vis_imp_cert_div").hide();

        $("#scanned_orth_han_cert").removeAttr("required");
        $("#scanned_orth_han_cert").val("");
        $('.orthopedically_handicapped_n').prop('checked', 'checked');
        $("#orth_han_cert_div").hide();

        $("#scanned_cer_palsy_cert").removeAttr("required");
        $("#scanned_cer_palsy_cert").val("");
        $('.cerebral_palsy_n').prop('checked', 'checked');
        $("#cer_palsy_cert_div").hide();

        $("#benchmark_disability_div").hide();
        $(".scribe_div").hide();
    });

    if ($(".benchmark_disability_y").prop("checked")) {
        $("#benchmark_disability_div").show();
    }

    /* Visually impaired certificate */
    if ($("#scanned_vis_imp_cert").val() != "") {
        $("#vis_imp_cert_div").show();
    } else {
        $("#scanned_vis_imp_cert").removeAttr("required");
    }

    if ($(".visually_impaired_y").prop("checked")) {
        $("#scanned_vis_imp_cert").prop('required', true);
        $("#vis_imp_cert_div").show();
    } else {
        $("#scanned_vis_imp_cert").removeAttr("required");
        $("#scanned_vis_imp_cert").val("");
        $("#vis_imp_cert_div").hide();
    }

    $(document).on("click", ".visually_impaired_y", function() {
        $("#scanned_vis_imp_cert").prop('required', true);
        $("#vis_imp_cert_div").show();
    });
    $(document).on("click", ".visually_impaired_n", function() {
        $("#scanned_vis_imp_cert").removeAttr("required");
        $("#scanned_vis_imp_cert").val("");
        $("#vis_imp_cert_div").hide();
    });

    /* Orthopedically handicapped certificate */
    if ($("#scanned_orth_han_cert").val() != "") {
        $("#orth_han_cert_div").show();
    } else {
        $("#scanned_orth_han_cert").removeAttr("required");
    }

    if ($(".orthopedically_handicapped_y").prop("checked")) {
        $("#scanned_orth_han_cert").prop('required', true);
        $("#orth_han_cert_div").show();
    } else {
        $("#scanned_orth_han_cert").removeAttr("required");
        $("#scanned_orth_han_cert").val("");
        $("#orth_han_cert_div").hide();
    }

    $(document).on("click", ".orthopedically_handicapped_y", function() {
        $("#scanned_orth_han_cert").prop('required', true);
        $("#orth_han_cert_div").show();
    });
    $(document).on("click", ".orthopedically_handicapped_n", function() {
        $("#scanned_orth_han_cert").removeAttr("required");
        $("#scanned_orth_han_cert").val("");
        $("#orth_han_cert_div").hide();
    });

    /* Cerebral palsy certificate */
    if ($("#scanned_cer_palsy_cert").val() != "") {
        $("#cer_palsy_cert_div").show();
    } else {
        $("#scanned_cer_palsy_cert").removeAttr("required");
    }

    if ($(".cerebral_palsy_y").prop("checked")) {
        $("#scanned_cer_palsy_cert").prop('required', true);
        $("#cer_palsy_cert_div").show();
    } else {
        $("#scanned_cer_palsy_cert").removeAttr("required");
        $("#scanned_cer_palsy_cert").val("");
        $("#cer_palsy_cert_div").hide();
    }

    $(document).on("click", ".cerebral_palsy_y", function() {
        $("#scanned_cer_palsy_cert").prop('required', true);
        $("#cer_palsy_cert_div").show();
    });
    $(document).on("click", ".cerebral_palsy_n", function() {
        $("#scanned_cer_palsy_cert").removeAttr("required");
        $("#scanned_cer_palsy_cert").val("");
        $("#cer_palsy_cert_div").hide();
    });

});