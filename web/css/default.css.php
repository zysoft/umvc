a img {
  border-style: none;
}

#center {
  min-weight: 640px;
  max-weight: 800px;
  margin: 0 auto 0 auto;
}
#menu {
  display: block;
  height: 26px;
  margin: 0;
  padding: 0;
  list-style-type: none;
  background-color: #000;
  color: #fff;
  text-transform: uppercase;
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