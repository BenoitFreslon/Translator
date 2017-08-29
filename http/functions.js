function update( form )
{
    //alert("update: " + form);
    console.log("update "+ form);
    console.log("update "+ form.action.value);
    console.log("update "+ form.new_translate.value);

    var formData = new FormData(form);
    formData.append("action", "update");

    var oldValue = form.submit.value;

    form.submit.value = "Updating...";
    form.submit.disabled = true;
    form.new_translate.disabled = true;

    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (this.readyState == XMLHttpRequest.DONE && this.status == 200) {
            console.log ("repsonseText: "+ this.responseText);
            //alert(this.responseText);
            form.new_translate.style.color = "#000000";
            form.submit.style.backgroundColor = "#EEEEEE";
            form.submit.style.color = "#000000";
            form.submit.value = oldValue;
            form.submit.disabled = false;
            form.new_translate.disabled = false;
        }
    };
    xmlhttp.open("POST", "ajax.php", true);
    xmlhttp.send(formData);
    var id = parseInt(form.input_id.value) + 1;
    var nextInputId = "input_" + id;
    console.log("nextInputId: "+ nextInputId);
    document.getElementById(  nextInputId ).focus();
    document.getElementById( nextInputId ).select();
}