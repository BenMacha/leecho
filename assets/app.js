/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */
import 'jquery/src/jquery';
import 'bootstrap-table/src/bootstrap-table';
const routes = require('../public/js/fos_js_routes.json');
import Routing from '../vendor/friendsofsymfony/jsrouting-bundle/Resources/public/js/router.min.js';
Routing.setRoutingData(routes);


// any CSS you import will output into a single css file (app.css in this case)
import './styles/app.scss';

function slugify(str) {
    str = str.replace(/^\s+|\s+$/g, ''); // trim leading/trailing white space
    str = str.toLowerCase(); // convert string to lowercase
    str = str.replace(/[^a-z0-9 -]/g, '') // remove any non-alphanumeric characters
        .replace(/\s+/g, '-') // replace spaces with hyphens
        .replace(/-+/g, '-'); // remove consecutive hyphens
    return str;
} // FROM INTERNET

$('body').ready(function () {

    $('.simple-table').bootstrapTable({
        pagination: true,
        search: true,
        pageSize: 10,
        pageList: [10, 25, 50],
        sortable: true
    })

    $(document).on('change', '#article_title', function () {
        let values = $(this).val();
        $('#article_slug').val(slugify(values))
    });

    $('.article-table').bootstrapTable({
        toggle: "table",
        sidePagination: "server",
        paginationLoop: true,
        virtualScroll: true,
        pagination: true,
        formatLoadingMessage: function () {
            return '<b>Loading ...</b>';
        },
        search: true,
        sortable: true,
        method: 'GET',
        url: Routing.generate("app_article_index"),
        pageSize: 10,
        pageList: [10, 25, 50],
        columns: [
            {
                title: 'Id',
                field: 'id',
                sortable: true,
                align: "center",
                sortName: 'article.id',
            },
            {
                title: 'Title',
                field: 'title',
                sortable: true,
                align: "center",
                sortName: 'article.title',
            },
            {
                title: 'Slug',
                field: 'slug',
                sortable: true,
                align: "center",
                sortName: 'article.slug',
            },
            {
                title: 'CreatedAt',
                field: 'createdAt',
                sortable: true,
                align: "center",
                sortName: 'article.createdAt',
            },
            {
                title: 'UpdatedAt',
                field: 'updatedAt',
                sortable: true,
                align: "center",
                sortName: 'article.updatedAt',
            },
            {
                title: 'Actions',
                sortable: false,
                align: "center",

                formatter : function(value,row,index) {

                    return '<a href="'+ Routing.generate("app_article_show", {'id': row.id}) +'">show</a>'
                        + ' <a href="'+Routing.generate("app_article_edit", {'id': row.id})+'">edit</a>'
                }
            },
        ]
    })

});