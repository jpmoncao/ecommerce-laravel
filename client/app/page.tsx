import Link from "next/link";
import { Card, CardContent } from '@/components/ui/card';
import { cn } from '@/lib/utils'

const colorClasses: Record<string, string> = {
  indigo: "from-indigo-400 to-indigo-700 border-indigo-200 shadow-indigo-400",
  purple: "from-purple-400 to-purple-700 border-purple-200 shadow-purple-400",
  green: "from-green-400 to-green-700 border-green-200 shadow-green-400",
  red: "from-red-400 to-red-700 border-red-200 shadow-red-400",
  sky: "from-sky-400 to-sky-700 border-sky-200 shadow-sky-400",
};

function RouteCard({ title, href, color }: { title: string, href: string, color: string }) {
  if (!color) return <></>;

  return (
    <Link href={href}>
      <Card className={cn(
        'w-[300px] h-[180px] bg-gradient-to-br shadow-xl flex items-center justify-center rounded-2xl hover:transform hover:scale-105 transition-transform duration-300',
        colorClasses[color]
      )}>
        <CardContent className="text-3xl text-white font-bold">
          {title}
        </CardContent>
      </Card>
    </Link>
  );
}

export default function Home() {
  return (
    <main className="flex justify-center items-center flex-wrap gap-12 min-h-screen">
      <RouteCard title='Listar Produtos' href='/products' color='sky' />
      <RouteCard title='Criar Produto' href='/create-product' color='purple' />
      <RouteCard title='Estoque' href='/stock' color='red' />
    </main>
  );
}
