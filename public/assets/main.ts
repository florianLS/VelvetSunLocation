export function test(arg: string, arg2: number): Record<string, number> {
      console.log("test");
      return {
            test: 1,
            test2: arg2
      };
}

const returnTest = test("test",2);
console.log(returnTest);