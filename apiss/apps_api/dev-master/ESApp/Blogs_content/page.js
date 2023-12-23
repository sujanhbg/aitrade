//---------------------------------------------------------------Starting Page
page(app + '/blogs_content', function () {
        blogs_contentdl();
});
//-------------------------------------------------------------New Form & actions
page(app + '/blogs_content/new', function () {
        fetch(app + '/?app=blogs_content&opt=new&fd=fd')
                .then((response) => response.text())
                .then((data) => {
                        $("#mainbody").html(data);
                });
});
//----------------------------------------------------------------Edit form and actions
page(app + '/blogs_content/edit/:id', function (ctx) {
        fetch(app + '/?app=blogs_content&opt=edit&fd=fd&ID=' + ctx.params.id)
                .then((response) => response.text())
                .then((data) => {
                        $("#mainbody").html(data);
                });

});

//----------------------------------------------------------------------Page View module        
page(app + '/blogs_content/view/:id', function (ctx) {
        fetch(app + '/?app=blogs_content&opt=view&json=json&ID=' + ctx.params.id)
                .then((response) => response.json())
                .then((data) => {
                        let htmlp = '<div class="card w3-padding"><div class="card-title"><h3>Blogs Content </h3></div><div class="card-content"><table class="table table-sm">';
                        for (const dt of data) {
                                htmlp += `<tr><td>ID </td><td></td><td>${dt.ID}</td></tr><tr><td>CategoryID </td><td></td><td>${dt.categoryID}</td></tr><tr><td>Title </td><td></td><td>${dt.title}</td></tr><tr><td>Blog Content </td><td></td><td>${dt.blog_content}</td></tr><tr><td>Short Desc </td><td></td><td>${dt.short_desc}</td></tr><tr><td>Defimage </td><td></td><td>${dt.defimage}</td></tr><tr><td>Alternative Title </td><td></td><td>${dt.alternative_title}</td></tr><tr><td>Keywords </td><td></td><td>${dt.keywords}</td></tr><tr><td>Publisher </td><td></td><td>${dt.publisher}</td></tr><tr><td>Date Created </td><td></td><td>${dt.date_Created}</td></tr><tr><td>Date Modified </td><td></td><td>${dt.date_Modified}</td></tr><tr><td>Date Published </td><td></td><td>${dt.date_Published}</td></tr><tr><td>InLanguage </td><td></td><td>${dt.inLanguage}</td></tr><tr><td>IsFamilyFriendly </td><td></td><td>${dt.isFamilyFriendly}</td></tr><tr><td>CopyrightYear </td><td></td><td>${dt.copyrightYear}</td></tr><tr><td>Genre </td><td></td><td>${dt.genre}</td></tr><tr><td>Deleted </td><td></td><td>${dt.deleted}</td></tr><tr><td>Published </td><td></td><td>${dt.published}</td></tr>`;
                        }
                        htmlp += `</table></div></div>`;
                        $("#mainbody").html(htmlp);
                });

});

//----------------------------------------------------------------------------delete data row
page(app + '/blogs_content/delete/:id', function (ctx) {
        fetch(app + '/blogs_content/delete/' + ctx.params.id)
                .then((response) => response.text())
                .then((data) => {
                        $("#modalbody").html(data);
                        openmodal();
                });

});

//------------------------------------------------------------------creating functions for calling in varius actions       
function blogs_contentdl() {
        document.title = "Blogs Content ";
        $("#mainbody").html(`<div class="card card-dark" style="transition: all 0.15s ease 0s; height: inherit; width: inherit;"><div class="card-header"><h3 class="card-title"><span class="material-icons">apps</span> blogs_content</h3><div class="card-tools">
<a href="${app}/blogs_content/new"><button type="button" class="btn btn-tool btn-primary"> <i class="fas fa-plus"> </i> New blogs_content </button> </a><button type="button" class="btn btn-tool" data-card-widget="maximize"><i class="fas fa-expand"></i></button> </div> </div><div class="card-body"><table id="blogs_contentdatatable" class="table-sm table table-striped table-hover dt-responsive display nowrap" style="width:100%"><thead><tr class="bg-gradient-dark"><th>ID </th><th>CategoryID </th><th>Title </th><th>Blog Content </th><th>Short Desc </th><th>Defimage </th><th>Alternative Title </th><th>Keywords </th><th>Publisher </th><th>Date Created </th><th>Date Modified </th><th>Date Published </th><th>InLanguage </th><th>IsFamilyFriendly </th><th>CopyrightYear </th><th>Genre </th><th>Deleted </th><th>Published </th><th style="width:100px;">Action</th></tr></thead></table></div></div>`);
        $('#blogs_contentdatatable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                        url: app + '/?app=blogs_content&opt=datasource&fd=fd',
                        type: 'POST'
                }, "columnDefs": [{
                        "targets": [- 1],
                        "searchable": false,
                        "orderable": false
                }],
                columns: [
                        { data: 0 }, { data: 1 }, { data: 2 }, { data: 3 }, { data: 4 }, { data: 5 }, { data: 6 }, { data: 7 }, { data: 8 }, { data: 9 }, { data: 10 }, { data: 11 }, { data: 12 }, { data: 13 }, { data: 14 }, { data: 15 }, { data: 16 }, { data: 17 }, {
                                data: 0, render: function (data, type) {
                                        return `<div class="btn-group"><button type="button" class="btn btn-default btn-sm">Action</button><button type="button" class="btn btn-default btn-sm dropdown-toggle dropdown-icon" data-toggle="dropdown" aria-expanded="false"><span class="sr-only">Toggle Dropdown</span></button><div class="dropdown-menu" role="menu" style=""><a class="dropdown-item text-primary" href="${app}/blogs_content/edit/${data}"><span class="material-icons">edit</span> Edit</a><a class="dropdown-item text-info" href="${app}/blogs_content/view/${data}"><span class="material-icons">preview</span> View</a><div class="dropdown-divider"></div><a class="dropdown-item text-danger" href="${app}/blogs_content/delete/${data}"><span class="material-icons">delete</span> Delete</a></div></div>`;
                                }
                        }
                ]
        });

}
