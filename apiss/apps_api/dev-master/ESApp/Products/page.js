//---------------------------------------------------------------Starting Page
     page(app + '/products', function () {
        productsdl();
        });
//-------------------------------------------------------------New Form & actions
        page(app + '/products/new', function () {
        fetch(app + '/?app=products&opt=new&fd=fd')
                .then((response) => response.text())
                .then((data) => {
                $("#mainbody").html(data);
                });
        });
//----------------------------------------------------------------Edit form and actions
        page(app + '/products/edit/:id', function (ctx) {
        fetch(app + '/?app=products&opt=edit&fd=fd&ID='+ctx.params.id)
                .then((response) => response.text())
                .then((data) => {
                $("#mainbody").html(data);
                });
        
        });

//----------------------------------------------------------------------Page View module        
        page(app + '/products/view/:id', function (ctx) {
        fetch(app + '/?app=products&opt=view&json=json&ID='+ctx.params.id)
                .then((response) => response.json())
                .then((data) => {
                 let htmlp = '<div class="card w3-padding"><div class="card-title"><h3>Products </h3></div><div class="card-content"><table class="table table-sm">';
                for (const dt of data) {
                    htmlp += `<tr><td>Product Name </td><td></td><td>${dt.product_name}</td></tr><tr><td>Category </td><td></td><td>${dt.category}</td></tr><tr><td>Barcode </td><td></td><td>${dt.barcode}</td></tr><tr><td>Brand Name </td><td></td><td>${dt.brand_name}</td></tr><tr><td>Supplier ID </td><td></td><td>${dt.supplier_ID}</td></tr><tr><td>Cost Price </td><td></td><td>${dt.cost_price}</td></tr><tr><td>Sale Price </td><td></td><td>${dt.sale_price}</td></tr><tr><td>Discounted Price </td><td></td><td>${dt.discounted_price}</td></tr><tr><td>Pic Url </td><td></td><td>${dt.pic_url}</td></tr>`;
                }
                htmlp += `</table></div></div>`;
                $("#mainbody").html(htmlp);
                });
        
        });

//----------------------------------------------------------------------------delete data row
        page(app + '/products/delete/:id', function (ctx) {
        fetch(app + '/products/delete/'+ctx.params.id)
                .then((response) => response.text())
                .then((data) => {
                $("#modalbody").html(data);
                openmodal();
                });
        
        });

//------------------------------------------------------------------creating functions for calling in varius actions       
    function productsdl(){
        document.title = "Products ";
$("#mainbody").html(`<div class="card card-dark" style="transition: all 0.15s ease 0s; height: inherit; width: inherit;"><div class="card-header"><h3 class="card-title"><span class="material-icons">apps</span> products</h3><div class="card-tools">
<a href="${app}/products/new"><button type="button" class="btn btn-tool btn-primary"> <i class="fas fa-plus"> </i> New products </button> </a><button type="button" class="btn btn-tool" data-card-widget="maximize"><i class="fas fa-expand"></i></button> </div> </div><div class="card-body"><table id="productsdatatable" class="table-sm table table-striped table-hover dt-responsive display nowrap" style="width:100%"><thead><tr class="bg-gradient-dark"><th>Product Name </th><th>Category </th><th>Barcode </th><th>Brand Name </th><th>Supplier ID </th><th>Cost Price </th><th>Sale Price </th><th>Discounted Price </th><th>Pic Url </th><th style="width:100px;">Action</th></tr></thead></table></div></div>`);
        $('#productsdatatable').DataTable({
processing: true,
        serverSide: true,
        ajax: {
        url: app + '/?app=products&opt=datasource&fd=fd',
                type: 'POST'
        }, "columnDefs": [{
"targets": [ - 1 ],
        "searchable": false,
        "orderable": false
}],
        columns: [
        {data: 0},{data: 1},{data: 2},{data: 3},{data: 4},{data: 5},{data: 6},{data: 7},{data: 8},{data: 0,render: function (data, type) {
                    return `<div class="btn-group"><button type="button" class="btn btn-default btn-sm">Action</button><button type="button" class="btn btn-default btn-sm dropdown-toggle dropdown-icon" data-toggle="dropdown" aria-expanded="false"><span class="sr-only">Toggle Dropdown</span></button><div class="dropdown-menu" role="menu" style=""><a class="dropdown-item text-primary" href="${app}/products/edit/${data}"><span class="material-icons">edit</span> Edit</a><a class="dropdown-item text-info" href="${app}/products/view/${data}"><span class="material-icons">preview</span> View</a><div class="dropdown-divider"></div><a class="dropdown-item text-danger" href="${app}/products/delete/${data}"><span class="material-icons">delete</span> Delete</a></div></div>`;
                }}
        ]
});
   
    }
        