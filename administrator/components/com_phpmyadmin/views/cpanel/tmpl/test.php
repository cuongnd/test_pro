<form method="post" action="index.php?option=com_phpmyadmin&task=diagram.test123">
    <fieldset data-role="controlgroup">
        <legend>Choose your gender:</legend>
        <label for="male">Male</label>
        <input type="radio" name="gender" id="male" value="male" checked>
        <label for="female">Female</label>
        <input type="radio" name="gender" id="female" value="female">
    </fieldset>

    <fieldset data-role="controlgroup">
        <legend>Choose as many favorite colors as you'd like:</legend>
        <label for="red">Red</label>
        <input type="checkbox" name="favcolor" id="red" value="red" checked>
        <label for="green">Green</label>
        <input type="checkbox" name="favcolor" id="green" value="green">
        <label for="blue">Blue</label>
        <input type="checkbox" name="favcolor" id="blue" value="blue" checked>
    </fieldset>
    <input type="hidden" name="controller" value="diagram"/>
    <input type="hidden" name="task" value="test123"/>
    <input type="hidden" name="option" value="com_phpmyadmin"/>
    <input type="submit" data-inline="true" value="Submit">

</form>