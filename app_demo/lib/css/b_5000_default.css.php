body {
  padding: 0;
  margin: 0;
  background-color: #eee;
  font-family: arial, sans-serif;
  font-size: 12px;
  line-height: 20px;
}

a img {
  border-style: none;
}

#center {
  width: 740px;
  margin: 0 auto 0 auto;
  background: #eee url('<?=$uf_dir_web_lib?>/images/background.gif') repeat-y left top;
}

#header {
  padding: 20px 40px 0 40px;
}

#content {
  padding: 10px 40px 0 40px;
}

#footer {
  padding: 5px 20px 0 20px;
  height: 19px;
  background: #eee url('<?=$uf_dir_web_lib?>/images/footer.gif') repeat-y left top;
  text-align: right;
  color: #666;
  font-size: 10px;
}

#menu {
  display: block;
  height: 26px;
  margin: 0 20px 0 20px;
  padding: 0;
  list-style-type: none;
  background-color: #000;
  color: #fff;
  text-transform: uppercase;
  border-bottom: 4px solid #f90;
}

#menu li {
  display: block;
  float: left;
}

#menu li a {
  display: block;
  height: 26px;
  padding: 0 12px 0 12px;
  line-height: 26px;
  color: #fff;
  border-style: none;
  outline: none;
  text-decoration: none;
}

#menu li a:hover {
  background-color: #f90;
}

#menu li.selected {
  background-color: #f90;
  color: #000;
}

#menu li.selected a {
  color: #000;
}