/*
 * This file is part of LunchBotBackend.
 *
 * Copyright (c) 2018 Benoît Frisch, Haris Dzogovic, Philipp Machauer, Pierre Bonert, Xiang Rong Lin
 *
 * LunchBotBackend is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.
 *
 * LunchBotBackend is distributed in the hope that it will be useful,but WITHOUT ANY WARRANTY; without even the implied warranty ofMERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with LunchBotBackend If not, see <http://www.gnu.org/licenses/>.
 *
 */

$(function () {
    $('[data-toggle="popover"]').popover();

    $('[data-toggle="tooltip"]').tooltip();

    $('select').select2();

    $('#meal_date').datetimepicker({
        format: 'YYYY-MM-DD',
    });


    $('.form-group div[data-prototype]').each(function () {
        console.log($(this));
        var $collectionHolder = $(this);
        var $addLink          = $('<a href="#" class="btn btn-primary add-link"><span class="oi oi-plus"></span> Key Term</a>'); // TODO: Make more specific "Add additional link", etc

        $collectionHolder.data('index', $collectionHolder.find(':input').length);

        $collectionHolder.append($addLink);

        $addLink.on('click', function (e) {
            e.preventDefault();

            var prototype = $collectionHolder.data('prototype');
            var index     = $collectionHolder.data('index');

            var newForm = prototype;

            // You need this only if you didn't set 'label' => false in your skills field in TaskType
            // Replace '__name__label__' in the prototype's HTML to
            // instead be a number based on how many items we have

            var label = index + '. Key Term';
            newForm   = newForm.replace(/__name__label__/g, label);

            // Replace '__name__' in the prototype's HTML to
            // instead be a number based on how many items we have
            newForm = newForm.replace(/__name__/g, index);

            var $newForm = $(newForm);
            $collectionHolder.data('index', index + 1);

            var $removeLink = $('<a href="#" class="btn btn-danger remove-link"><span class="oi oi-x"></span> </a>');
            $newForm.append($removeLink);
            $collectionHolder.append($newForm);

            // handle the removal, just for this example
            $removeLink.click(function (e) {
                e.preventDefault();
                $newForm.remove();
                return false;
            });
        });
    });
});

