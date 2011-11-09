#!/usr/bin/perl

use strict;
use warnings;

my $modo_de_uso = "Modo de uso:
./script \"/home/usuario/data_dictionary\" \"/home/usuario2/data_dictionary\"

ATENÇÃO: Para efetivamente fazer as mudanças no disco, use --vai depois
do segundo argumento.
";

# Checa argumentos do scripts

defined $ARGV[0]
  or die "Erro: Argumento 1 não informado.\n\n$modo_de_uso";

die $modo_de_uso if ($ARGV[0] =~ /^help$|^-help$|^--help$/);

defined $ARGV[1]
  or die "Erro: Argumento 2 não informado.\n\n$modo_de_uso";

# Checa se rodará em modo de teste ou não

my $vai = defined $ARGV[2] && $ARGV[2] =~ /^--vai$/;

# Limpa origem e destino de barras invertidas ("\")

my $origem = $ARGV[0];
$origem =~ s/\\//g;

my $destino = $ARGV[1];
$destino =~ s/\\//g;

print "Origem principal: $origem\n";
print "Destino principal: $destino\n";

my @data_dict_folders = ("Web Scripts Extensions", "Scripts",
			 "Web Client Extension", "Models",
			 "Workflow Definitions");

foreach (@data_dict_folders) {
  my $comando = "diff -rq \"$origem/$_\" \"$destino/$_\" | grep -v .svn";

  print "\nOrigem interna: \"$origem/$_\"\n";
  print "Destino interno: \"$destino/$_\"\n";

  # Roda o diff

  open my $dados, '-|', $comando or die "Erro na função open().\n";

  # Loop

  while (<$dados>) {
    if ($_ =~ /^Files (.+) and (.+) differ$/) { # diffoutput: Files X and Y differ
      print "\n  $_\n  info: cp -rv \"$1\" \"$2\"\n";

      if ($vai) {
	system("cp -rv \"$1\" \"$2\"");
	print "Ok.\n";
      }
    } elsif ($_ =~ /^Only in (.+): (.+)$/) { # diffoutput: Only in X: Y
      print "\n  $_";

      my $only_in_dir = $1;
      my $only_in_file = $2;
      my $only_in_complete = "$only_in_dir/$only_in_file";

      if ($only_in_dir eq $origem) {
	print "  info: cp -rv \"$only_in_complete\" \"$destino\"\n";

	if ($vai) {
	  system("cp -rv \"$only_in_complete\" \"$destino\"");
	  print "  Ok com essa linha.\n";
	}
      } elsif (grep {/$origem/} $only_in_dir) {
	my $diferenca = $only_in_complete;
	$diferenca =~ s/$origem//;

	print "  info: cp -rv \"$origem$diferenca\" \"$destino$diferenca\"\n";

	if ($vai) {
	  system("cp -rv \"$origem$diferenca\" \"$destino$diferenca\"");
	  print "  Ok com essa linha.\n";
	}
      }
    } else {
      print "\n  $_\n  Nada feito com essa linha.\n";
    }
  }

}

print "\nATENÇÃO:\nScript rodado em modo teste. Confira as informações acima.\n"
  . "Para rodar efetivamente, adicone --vai depois do segundo argumento.\n"
  unless ($vai);
