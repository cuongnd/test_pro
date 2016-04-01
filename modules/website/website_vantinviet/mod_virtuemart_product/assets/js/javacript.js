jQuery(document).ready(function($){
    //loadProductImage();
    function loadProductImage()
    {
        listProduct=new Array();
        $('.item-product.load-image').each(function(){
            product_id=$(this).attr('data-product-id');
            listProduct.push(product_id);
        });
        $.ajax({
            type: "GET",
            dataType: "json",
            url: 'index.php',
            data: (function() {
                $data = {
                    option: 'com_virtuemart',
                    controller: 'utilities',
                    task: 'loadImageProduct',
                    listProduct:listProduct

                };
                return $data;
            })(),
            beforeSend: function() {

            },
            success: function(result) {
 

                for(i=0;i<result.length;i++)
                {
                    //console.log(result[i].virtuemart_product_id);
                    file_url=result[i].file_url;
                    if(file_url!==null)
                        $('#product-'+result[i].virtuemart_product_id.toString()+' img.featuredProductImage').attr('src',(result[i].file_url.toString()));
                }



            }
        });

    }
});