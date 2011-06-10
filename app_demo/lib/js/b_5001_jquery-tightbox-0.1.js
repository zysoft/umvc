/*
 * Project: Tightbox v0.1
 *
 * @author David Brännvall http://www.untitledjs.com, Copyright (C) 2011.
 * @see The GNU Public License (GPL)

 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful, but
 * WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY
 * or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License
 * for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 59 Temple Place, Suite 330, Boston, MA 02111-1307 USA
 */
(function() {
  var is_init = false;
  var is_active = false;
  var instance = 0;
  var $overlay;
  var $content;

  var methods = {
    init: function(options) {
      return this.each(function() {
        var $this = $(this);
        var data = $this.data('tightbox');
        if(!data) {
          $this.data('tightbox', {options: options});
        }
        $this.bind('click.tightbox', methods.show);        
      });
    },
    
    show: function() {
      var options = {
        width: 400,
        overlay_class: 'tightbox_overlay',
        content_class: 'tightbox_content'
      };

      var $this = $(this);
      var data = $this.data('tightbox');
      settings = data.options;

      if(options) { 
        $.extend(options, settings);
      }

      if(is_init || is_active) return false;
      is_init = true;

      $('body').prepend('<div class="'+options.overlay_class+'"></div>');
      $('body').prepend(
        '<div class="'+options.content_class+'">' +
          '<div class="content"></div>' +
        '</div>');

      $overlay = $('.'+options.overlay_class);
      $content = $('.'+options.content_class);

      $overlay.css({
        display: 'none',
        position: 'fixed',
        left: 0,
        top: 0,
        width: '100%',
        height: '100%',
        backgroundColor: '#000',
        opacity: 0.5,
        zIndex: 10000
      });

      $content.css({
        display: 'none',
        position: 'fixed',
        left: '50%',
        top: '50%',
        zIndex: '10001'
      });
      
      $.post(this.href, function(data) {
        $content.html(data);

        if(options.width) $content.width(options.width);
        if(options.height) $content.height(options.height);

        $content.css({
          marginLeft: -($content.outerWidth() / 2)+'px',
          marginTop: -($content.outerHeight() / 2)+'px'
        });

        $overlay.fadeIn('fast');
        $content.fadeIn('fast');

        is_active = true;
      });

      is_init = false;
      
      return false;
    },
    
    close: function() {
      is_active = false;
      $overlay.fadeOut('fast', function() {
        $overlay.remove();
      });
      $content.fadeOut('fast', function() {
        $content.remove();
      });
    }
  };

  jQuery.fn.tightbox = function(method) {
    if(methods[method]) {
      return methods[method].apply(this, Array.prototype.slice.call(arguments, 1));
    } else if (typeof method === 'object' || !method) {
      return methods.init.apply(this, arguments);
    } else {
      $.error('Method '+method+' does not exist on jQuery.tightbox' );
    }
  };
})();