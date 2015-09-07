<script id="row-template" type="text/x-kendo-template">
    <tr data-uid="#: uid #">
        <td class="photo">
            <img src="../content/web/Employees/#:EmployeeID#.jpg" alt="#: EmployeeID #" />
        </td>
        <td class="details">
            <span class="title">#: Title #</span>
            <span class="description">Name : #: FirstName# #: LastName#</span>
            <span class="description">Country : #: Country# </span>
        </td>
        <td class="employeeID">
            #: EmployeeID #
        </td>
    </tr>
</script>

<script id="alt-row-template" type="text/x-kendo-template">
    <tr class="k-alt" data-uid="#: uid #">
        <td class="photo">
            <img src="../content/web/Employees/#:EmployeeID#.jpg" alt="#: EmployeeID #" />
        </td>
        <td class="details">
            <span class="title">#: Title #</span>
            <span class="description">Name : #: FirstName# #: LastName#</span>
            <span class="description">Country : #: Country# </span>
        </td>
        <td class="employeeID">
            #: EmployeeID #
        </td>
    </tr>
</script>