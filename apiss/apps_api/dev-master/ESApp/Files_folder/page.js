//---------------------------------------------------------------Starting Page
     page(app + '/files_folder', function () {
        files_folderdl();
        });
//-------------------------------------------------------------New Form & actions
        page(app + '/files_folder/new', function () {
        fetch(app + '/?app=files_folder&opt=new&fd=fd')
                .then((response) => response.text())
                .then((data) => {
                $("#mainbody").html(data);
                });
        });
//----------------------------------------------------------------Edit form and actions
        page(app + '/files_folder/edit/:id', function (ctx) {
        fetch(app + '/?app=files_folder&opt=edit&fd=fd&ID='+ctx.params.id)
                .then((response) => response.text())
                .then((data) => {
                $("#mainbody").html(data);
                });
        
        });

//----------------------------------------------------------------------Page View module        
        page(app + '/files_folder/view/:id', function (ctx) {
        fetch(app + '/?app=files_folder&opt=view&json=json&ID='+ctx.params.id)
                .then((response) => response.json())
                .then((data) => {
                 let htmlp = '<div class="card w3-padding"><div class="card-title"><h3>Files Folder </h3></div><div class="card-content"><table class="table table-sm">';
                for (const dt of data) {
                    htmlp += `<tr><td>ID </td><td></td><td>${dt.ID}</td></tr><tr><td>Folder Name </td><td></td><td>${dt.folder_name}</td></tr><tr><td>Sub Id </td><td></td><td>${dt.sub_id}</td></tr><tr><td>Icon </td><td></td><td>${dt.icon}</td></tr>`;
                }
                htmlp += `</table></div></div>`;
                $("#mainbody").html(htmlp);
                });
        
        });

//----------------------------------------------------------------------------delete data row
        page(app + '/files_folder/delete/:id', function (ctx) {
        fetch(app + '/files_folder/delete/'+ctx.params.id)
                .then((response) => response.text())
                .then((data) => {
                $("#modalbody").html(data);
                openmodal();
                });
        
        });

//------------------------------------------------------------------creating functions for calling in varius actions       
    function files_folderdl(){
        document.title = "Files Folder ";
$("#mainbody").html(`<div class="card card-dark" style="transition: all 0.15s ease 0s; height: inherit; width: inherit;"><div class="card-header"><h3 class="card-title"><span class="material-icons">apps</span> files_folder</h3><div class="card-tools">
<a href="${app}/files_folder/new"><button type="button" class="btn btn-tool btn-primary"> <i class="fas fa-plus"> </i> New files_folder </button> </a><button type="button" class="btn btn-tool" data-card-widget="maximize"><i class="fas fa-expand"></i></button> </div> </div><div class="card-body"><table id="files_folderdatatable" class="table-sm table table-striped table-hover dt-responsive display nowrap" style="width:100%"><thead><tr class="bg-gradient-dark"><th>ID </th><th>Folder Name </th><th>Sub Id </th><th>Icon </th><th style="width:100px;">Action</th></tr></thead></table></div></div>`);
        $('#files_folderdatatable').DataTable({
processing: true,
        serverSide: true,
        ajax: {
        url: app + '/?app=files_folder&opt=datasource&fd=fd',
                type: 'POST'
        }, "columnDefs": [{
"targets": [ - 1 ],
        "searchable": false,
        "orderable": false
}],
        columns: [
        {data: 0},{data: 1},{data: 2},{data: 3},{data: 0,render: function (data, type) {
                    return `<div class="btn-group"><button type="button" class="btn btn-default btn-sm">Action</button><button type="button" class="btn btn-default btn-sm dropdown-toggle dropdown-icon" data-toggle="dropdown" aria-expanded="false"><span class="sr-only">Toggle Dropdown</span></button><div class="dropdown-menu" role="menu" style=""><a class="dropdown-item text-primary" href="${app}/files_folder/edit/${data}"><span class="material-icons">edit</span> Edit</a><a class="dropdown-item text-info" href="${app}/files_folder/view/${data}"><span class="material-icons">preview</span> View</a><div class="dropdown-divider"></div><a class="dropdown-item text-danger" href="${app}/files_folder/delete/${data}"><span class="material-icons">delete</span> Delete</a></div></div>`;
                }}
        ]
});
   
    }
        