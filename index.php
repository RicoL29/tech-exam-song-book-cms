<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PrepV1</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.2.min.js" integrity="sha256-2krYZKh//PcchRtd+H+VyyQoZ/e3EcrkxhM8ycwASPA=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
    <div class="container my-5">
        <button type="button" class="btn btn-primary" onclick="clearForm()" data-toggle="modal" data-target="#myModal"> New</button>
        <br><br>
        <table id="songTable" class="table table-striped table-sm">
            <thead>
                <tr>
                    <th colspan="5" class="text-uppercase bg-primary text-white">
                        Song List
                    </th>
                </tr>
                <tr>
                    <th>Title</th>
                    <th>Artist</th>
                    <th>Date Created</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>

    <div class="modal faded" id="myModal">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Song Form</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body px-5">
                <form method="post" id="songForm" class="form mx-auto">
                    <label for="title">Title:</label>
                    <input type="text" name="title" class="form-control" required>
                    <br>
                    <label for="artist">Artist:</label>
                    <input type="text" name="artist" class="form-control" required>
                    <br>
                    <label for="lyrics">Lyrics:</label>
                    <textarea name="lyrics" cols="30" rows="10" class="form-control"></textarea>
                    <br>
                    <input type="hidden" name="id" id="songId">
                    <button type="submit" class="btn btn-primary">Submit</button>
                    <button type="reset" class="btn btn-default">Clear</button>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>

            </div>
        </div>
    </div>
</body>
<script>
tableComponent();
function tableComponent() {
    $.ajax({
        url: 'controller/SongBookController.php',
        method: 'post',
        data: { 
            action: 'table'
        },
        success:function(result){
            var res = $.parseJSON(result);
            var output = '';
            if(res.length > 0){
                for (var i = 0; i < res.length; i++) {
                    var element = res[i];
                    output += '<tr>';
                    output += '<td>' + element.title + '</td>';
                    output += '<td>' + element.artist + '</td>';
                    output += '<td>' + element.created_at + '</td>';
                    output += '<td>';
                    output += '<button class="btn btn-sm btn-warning" onclick="fetch(' + element.id + ')">Edit</button>';
                    output += '<button class="btn btn-sm btn-danger" onclick="remove(' + element.id + ')">Remove</button>';
                    output += '</td>';
                    output += '</tr>';
                }
            } else {
                output += "<tr><td colspan='5' class='text-center'>No song available</td></tr>";
            }
			$('#songTable tbody').html(output);
		}
    });
}

function fetch(id) {
    $.ajax({
        url: 'controller/SongBookController.php',
        method: 'post',
        data: { 
            action: 'fetch',
            id: id
        },
        dataType: 'json',
        success:function(result){
            $('#songForm [name="title"]').val(result.title);
            $('#songForm [name="artist"]').val(result.artist);
            $('#songForm [name="lyrics"]').text(result.lyrics);
            $('#songForm [name="id"]').val(result.id);
            $("#myModal").modal('show');
        }
    });
}

function remove(id) {
    $.ajax({
        url: 'controller/SongBookController.php',
        method: 'post',
        data: { 
            action: 'remove',
            id: id
        },
        success:function(result){
            alert(result);
            tableComponent();
        }
    });
}

$('#songForm').on('submit', function(e){
    e.preventDefault();
    $.ajax({
        url: 'controller/SongBookController.php',
        method: 'post',
        data: {
            action: 'save',
            title: $('[name="title"]').val(),
            artist: $('[name="artist"]').val(),
            lyrics: $('[name="lyrics"]').val(),
            id: $('#songId').val()
        },
        success:function(result){
            alert(result);
            tableComponent();
            clearForm();
            $('#myModal').modal('hide');
        }
    });
});

function clearForm() {
    $('#songForm')[0].reset();
    $('#songId').val('');
    $('textarea[name="lyrics"]').text('');
}
</script>
</html>