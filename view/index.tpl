<section class="content index-content">
    <select id="selectCategory" name="service-category" class="select-drop-down" style="box-shadow: 1px 5px 15px 0px {$ps->theme.header.background_color}">
        <option id="-1" selected>Odaberite kategoriju</option>
        {html_options options=$items}
    </select>
</section>