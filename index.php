<!DOCTYPE HTML>
<html>

<head>
    <title>Directory Printer</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

    <script>
        function getData() {
            $("#resultTable").hide();
            $("tbody").children("tr").remove();
            $("#SearchedDir").html("");
            var dir = $("#dir").val();
            $("#dir").val("");
            if (dir == "") {
                handleAlert("Please insert a directory name.");
                return;
            }

            $.ajax({
                type: "POST",
                url: "ajax.php",
                data: "dir=" + dir,
                success: function(data) {
                    var table = document.querySelector("table tbody");
                    var response = JSON.parse(data);

                    if (response.files == '') {
                        handleAlert("Directory " + response.dirName + " is Empty.");
                        return;
                    }
                    if (data != 0) {
                        var files = response.files.split(", ");
                        var size = response.size.split(", ");
                        var lastModified = response.lastModified.split(", ");

                        for (var i = 0; i < files.length; i++) {
                            var tr = document.createElement("tr");
                            var tdIndex = document.createElement("td");
                            var tdFiles = document.createElement("td");
                            var tdSize = document.createElement("td");
                            var tdLastModified = document.createElement("td");

                            tdIndex.textContent = i + 1;
                            tdFiles.textContent = files[i];
                            tdSize.textContent = size[i];
                            tdLastModified.textContent = lastModified[i];

                            tr.appendChild(tdIndex);
                            tr.appendChild(tdFiles);
                            tr.appendChild(tdSize);
                            tr.appendChild(tdLastModified);

                            table.appendChild(tr);
                        }

                        $("#SearchedDir").html(response.dirName);
                        $("#resultTable").show();

                    } else {
                        handleAlert("Directory " + dir + " does not exist !")
                    }
                }
            })
        };

        function handleAlert(msg) {
            $("#alertDiv").html(msg);
            $("#alertDiv").show();
            setTimeout(function() {
                $("#alertDiv").hide();
            }, 2500);
        }
    </script>
</head>

<body>
    <div class="container"><br>
        <h4>Directory Printer</h4>
        <form onsubmit="getData(); return false;">
            <div class="form-group">
                <input id="dir" type="text" name="dir" class="form-control" placeholder="insert directory name" style="width: 200px; display: inline-block;" />
                <input type="submit" class="btn btn-primary" style="display: inline-block;margin-top:-3px" />
            </div>

        </form>
        <br>

        <h4 id="SearchedDir"></h4>
        <table id="resultTable" class="table table-striped" style="display: none;">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Files</th>
                    <th>Size</th>
                    <th>Last Modified</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
        <div id="alertDiv" class="alert alert-danger" role="alert" style="display: none;width:320px"> </div>
    </div>
</body>

</html>